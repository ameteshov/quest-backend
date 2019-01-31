<?php

use App\Http\Controllers\{
    AuthController, QuestionnaireController, UserController, PaymentController,
    PlanController, QuestionnaireTypeController, SocialAuthController
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

    Route::get('social-login/google', SocialAuthController::class.'@googleLogin');
    Route::get('social-login/google-plus-callback', SocialAuthController::class.'@googleLoginCallback');
});

Route::get('/auth/refresh', function() {
    return response('', \Illuminate\Http\Response::HTTP_NO_CONTENT);
})->middleware(['jwt.refresh']);

Route::middleware($authGroup)->group(function () {
    Route::post('questionnaires', QuestionnaireController::class.'@create');
    Route::get('questionnaires', QuestionnaireController::class.'@search');
    Route::get('questionnaires/{id}', QuestionnaireController::class.'@get')->where('id', '[0-9]+');
    Route::delete('questionnaires/{id}', QuestionnaireController::class.'@delete')->where('id', '[0-9]+');
    Route::put('questionnaires/{id}', QuestionnaireController::class.'@update')->where('id', '[0-9]+');

    Route::get('questionnaires/results/vacancies', QuestionnaireController::class.'@getResultsVacancies');
    Route::get('questionnaires/statistic', QuestionnaireController::class.'@getStatistic');

    Route::post('questionnaires/{id}/send', QuestionnaireController::class.'@send')->where('id', '[0-9]+');

    Route::post('users', UserController::class.'@create');
    Route::get('users', UserController::class.'@search');
    Route::get('users/{id}', UserController::class.'@get')->where('id', '[0-9]+');
    Route::delete('users/{id}', UserController::class.'@delete')->where('id', '[0-9]+');
    Route::put('users/{id}', UserController::class.'@update')->where('id', '[0-9]+');

    Route::post('plans', PlanController::class.'@create');
    Route::get('plans', PlanController::class.'@search');
    Route::get('plans/{id}', PlanController::class.'@get')->where('id', '[0-9]+');
    Route::delete('plans/{id}', PlanController::class.'@delete')->where('id', '[0-9]+');
    Route::put('plans/{id}', PlanController::class.'@update')->where('id', '[0-9]+');

    Route::post('questionnaires/types', QuestionnaireTypeController::class.'@create');
    Route::get('questionnaires/types', QuestionnaireTypeController::class.'@search');
    Route::get('questionnaires/types/{id}', QuestionnaireTypeController::class.'@get')->where('id', '[0-9]+');
    Route::delete('questionnaires/types/{id}', QuestionnaireTypeController::class.'@delete')->where('id', '[0-9]+');
    Route::put('questionnaires/types/{id}', QuestionnaireTypeController::class.'@update')->where('id', '[0-9]+');

    Route::post('payments', PaymentController::class.'@create');
    Route::get('payments', PaymentController::class.'@search');
});
