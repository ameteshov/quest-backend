<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Http\Response;

class PaymentTest extends TestCase
{
    protected $admin;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
    }

    public function testPay()
    {
        $response = $this->actingAs($this->user)->json('post', 'payments', [
            'plan_id' => 1
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
