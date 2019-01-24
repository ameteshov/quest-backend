<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuestionnairesResultsAddExpiredAt extends Migration
{
    public function up()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->dropColumn('expired_at');
        });
    }
}
