<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuestionnairesTableAddTypeIdField extends Migration
{
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->integer('type_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
    }
}
