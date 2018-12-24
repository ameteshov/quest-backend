<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairesResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires_results', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->nullable();
            $table->string('email', 100);
            $table->string('recipient_name', 100);
            $table->string('access_hash', 100);
            $table->boolean('is_passed')->default(false);
            $table->integer('questionnaire_id');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaires_results');
    }
}
