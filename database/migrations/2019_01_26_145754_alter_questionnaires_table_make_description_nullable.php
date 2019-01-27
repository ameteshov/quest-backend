<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuestionnairesTableMakeDescriptionNullable extends Migration
{
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });
    }
}
