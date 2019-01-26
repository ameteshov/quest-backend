<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuestionnairesTableRenameTypeField extends Migration
{
    public function up()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->renameColumn('type', 'result_type');
        });
    }

    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->renameColumn('result_type', 'type');
        });
    }
}
