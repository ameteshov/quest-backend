<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuestionnairesResultsTableAddVacancyField extends Migration
{
    public function up()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->string('vacancy')->nullable();
        });
    }

    public function down()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->dropColumn('vacancy');
        });
    }
}
