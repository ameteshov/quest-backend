<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTableAddSocialLoginFields extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(true)->change();
            $table->string('google_id')->nullable();
            $table->string('vk_id')->nullable();
            $table->string('facebook_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn('google_id');
            $table->dropColumn('vk_id');
            $table->dropColumn('facebook_id');
        });
    }
}
