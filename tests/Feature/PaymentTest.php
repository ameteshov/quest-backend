<?php

namespace Tests\Feature;

use App\Model\User;
use App\Service\PaymentService;
use App\Util\YandexPaymentClient;
use Illuminate\Http\Response;
use YandexCheckout\Client;
use YandexCheckout\Request\Payments\PaymentResponse;

class PaymentTest extends TestCase
{
    protected $admin;
    protected $user;
    protected $userWithActiveTransaction;
    protected $userPaid;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
        $this->userWithActiveTransaction = User::find(3);
        $this->userPaid = User::find(4);
    }

    public function testPay()
    {
        $serviceResponse = $this->getJsonFixture('payment_service_response.json');

        $this->mock(YandexPaymentClient::class, function ($item) use ($serviceResponse) {
            $item->shouldReceive('create')
                ->andReturn($serviceResponse);
        });

        $response = $this->actingAs($this->user)->json('post', 'payments', [
            'plan_id' => 1
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['url' => $serviceResponse['url']]);

        $this->assertDatabaseHas('payments', $serviceResponse['entity']);
    }

    public function testPayPlanNotExists()
    {
        $response = $this->actingAs($this->user)->json('post', 'payments', [
            'plan_id' => 0
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPayNoAuth()
    {
        $response = $this->json('post', 'payments', [
            'plan_id' => 0
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testPayTransactionAlreadyActive()
    {
        $response = $this->actingAs($this->userWithActiveTransaction)->json('post', 'payments', [
            'plan_id' => 2
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testHandlePayment()
    {
        $serviceResponse = $this->getJsonFixture('client_get_payment_info_response_success.json');

        $this->mock(Client::class, function ($item) use ($serviceResponse) {
            $item->shouldReceive('getPaymentInfo')
                ->andReturn(new PaymentResponse($serviceResponse))
                ->shouldReceive('setAuth')
                ->andReturn($this);
        });

        $notification = $this->getJsonFixture('payment_notification_success.json');

        $response = $this->json('post', 'payments/webhooks', $notification);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', [
            'id' => $this->userPaid->id,
            'points' => 100
        ]);
        $this->assertDatabaseHas('payments', [
            'payment_id' => $notification['object']['id'],
            'status' => YandexPaymentClient::PAYMENT_STATUS_SUCCESS,
            'is_paid' => 1
        ]);
        $this->assertDatabaseMissing('payment_transactions', ['user_id' => $this->userPaid->id]);
    }

    public function testHandleSubscription()
    {
        $serviceResponse = $this->getJsonFixture('client_get_payment_info_response_success.json');
        $service = app(PaymentService::class);
        $service->updateBy(['id' => 1], ['plan_id' => 5]);

        $this->mock(Client::class, function ($item) use ($serviceResponse) {
            $item->shouldReceive('getPaymentInfo')
                ->andReturn(new PaymentResponse($serviceResponse))
                ->shouldReceive('setAuth')
                ->andReturn($this);
        });

        $notification = $this->getJsonFixture('payment_notification_success.json');

        $response = $this->json('post', 'payments/webhooks', $notification);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', [
            'id' => $this->userPaid->id,
            'points' => 0
        ]);
        $this->assertDatabaseHas('payments', [
            'payment_id' => $notification['object']['id'],
            'status' => YandexPaymentClient::PAYMENT_STATUS_SUCCESS,
            'is_paid' => 1
        ]);
        $this->assertDatabaseMissing('payment_transactions', ['user_id' => $this->userPaid->id]);
    }

    public function testHandlePaymentCancelled()
    {
        $serviceResponse = $this->getJsonFixture('client_get_payment_info_response_cancelled.json');

        $this->mock(Client::class, function ($item) use ($serviceResponse) {
            $item->shouldReceive('getPaymentInfo')
                ->andReturn(new PaymentResponse($serviceResponse))
                ->shouldReceive('setAuth')
                ->andReturn($this);
        });

        $notification = $this->getJsonFixture('payment_notification_cancelled.json');

        $response = $this->json('post', 'payments/webhooks', $notification);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users', [
            'id' => $this->userPaid->id,
            'points' => $this->userPaid->points
        ]);
        $this->assertDatabaseHas('payments', [
            'payment_id' => $notification['object']['id'],
            'status' => YandexPaymentClient::PAYMENT_STATUS_CANCEL,
            'is_paid' => 0
        ]);
        $this->assertDatabaseMissing('payment_transactions', ['user_id' => $this->userPaid->id]);
    }

    public function testSearch()
    {
        $response = $this->actingAs($this->admin)->json('get', '/payments', []);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson($this->getJsonFixture('search.json'));
    }

    public function testSearchNoAuth()
    {
        $response = $this->json('get', '/payments', []);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testSearchNoPermission()
    {
        $response = $this->actingAs($this->user)->json('get', '/payments', []);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
