<?php

use App\Http\Controllers\{
    AuthController, QuestionnaireController, UserController, PaymentController,
    PlanController, EmployeeCharacteristicController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$authGroup = ['jwt.auth'];
$guestGroup = [];

Route::middleware($guestGroup)->group(function () {
    Route::post('auth/login', AuthController::class.'@login');
    Route::post('auth/reset-password', AuthController::class.'@resetPassword');
    Route::post('auth/confirm-password', AuthController::class.'@confirmPassword');
    Route::post('auth/register', AuthController::class.'@register');

    Route::get('forms/{hash}', QuestionnaireController::class.'@getByHash');
    Route::post('forms', QuestionnaireController::class.'@submit');

    Route::post('payments/webhooks', PaymentController::class.'@handle');
});

Route::get('/auth/refresh', function() {
    return response('', \Illuminate\Http\Response::HTTP_NO_CONTENT);
})->middleware(['jwt.refresh']);

Route::middleware($authGroup)->group(function () {
    Route::post('questionnaires', QuestionnaireController::class.'@create');
    Route::get('questionnaires', QuestionnaireController::class.'@search');
    Route::get('questionnaires/{id}', QuestionnaireController::class.'@get');
    Route::delete('questionnaires/{id}', QuestionnaireController::class.'@delete');
    Route::put('questionnaires/{id}', QuestionnaireController::class.'@update');

    Route::post('questionnaires/{id}/send', QuestionnaireController::class.'@send');

    Route::post('users', UserController::class.'@create');
    Route::get('users', UserController::class.'@search');
    Route::get('users/{id}', UserController::class.'@get');
    Route::delete('users/{id}', UserController::class.'@delete');
    Route::put('users/{id}', UserController::class.'@update');

    Route::post('plans', PlanController::class.'@create');
    Route::get('plans', PlanController::class.'@search');
    Route::get('plans/{id}', PlanController::class.'@get');
    Route::delete('plans/{id}', PlanController::class.'@delete');
    Route::put('plans/{id}', PlanController::class.'@update');

    Route::post('employee-characteristics', EmployeeCharacteristicController::class.'@create');
    Route::get('employee-characteristics', EmployeeCharacteristicController::class.'@search');
    Route::get('employee-characteristics/{id}', EmployeeCharacteristicController::class.'@get');
    Route::delete('employee-characteristics/{id}', EmployeeCharacteristicController::class.'@delete');
    Route::put('employee-characteristics/{id}', EmployeeCharacteristicController::class.'@update');

    Route::post('payments', PaymentController::class.'@create');
    Route::get('payments', PaymentController::class.'@search');
});
