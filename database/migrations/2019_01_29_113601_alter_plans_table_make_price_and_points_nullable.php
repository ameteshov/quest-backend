<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlansTableMakePriceAndPointsNullable extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('price')->nullable()->change();
            $table->integer('points')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('price')->nullable(false)->change();
            $table->integer('points')->nullable(false)->change();
        });
    }
}
