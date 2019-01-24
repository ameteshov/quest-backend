<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuesitonnairesResultsTableAddRecipientFields extends Migration
{
    public function up()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->timestamp('birthday_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->dropColumn('birthday_date');
        });
    }
}
