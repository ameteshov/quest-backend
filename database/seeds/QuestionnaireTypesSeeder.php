<?php

use Illuminate\Database\Seeder;

class QuestionnaireTypesSeeder extends Seeder
{
    public function run()
    {
        factory(\App\Model\QuestionnaireType::class)->create([
            'name' => 'Стрессоустойчивость'
        ]);
    }
}
