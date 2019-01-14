<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuestionnairesResultsTableAddRecipientInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->string('recipient_phone', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires_results', function (Blueprint $table) {
            $table->dropColumn('recipient_phone');
        });
    }
}
