<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Model\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'
    ];
});

$factory->define(App\Model\Questionnaire::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'content' => json_encode([
            'questions' => [$faker->sentence],
            'answers' => [$faker->sentence]
        ]),
        'description' => $faker->text(),
        'is_active' => true,
        'success_score' => $faker->numberBetween(5, 200),
        'result_type' => \App\Model\Questionnaire::SUM_TYPE,
        'type_id' => 1
    ];
});

$factory->define(App\Model\QuestionnaireType::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Model\QuestionnaireResult::class, function (Faker $faker) {
    return [
        'content' => json_encode([]),
        'email' => $faker->email,
        'recipient_name' => $faker->name,
        'recipient_phone' => $faker->phoneNumber,
        'access_hash' => $faker->sha256,
        'is_passed' => 0,
        'user_id' => 1,
        'questionnaire_id' => 1,
        'expired_at' => \Carbon\Carbon::now()->addHour(48)->toDateTimeString(),
        'birthday_date' => null,
        'score' => null,
        'vacancy' => null
    ];
});
