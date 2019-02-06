<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlansTableAddSlugField extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
