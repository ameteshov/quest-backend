<?php

use Illuminate\Database\Migrations\Migration;
use App\Model\User;
use App\Model\Role;

class AddUser extends Migration
{
    //TODO::rewrite credentials
    public function up()
    {
        if (env('APP_ENV') !== 'testing') {
            User::create([
                'name' => 'Jhon Doe',
                'email' => 'meteshov.artem@gmail.com',
                'password' => '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy',
                'role_id' => Role::ROLE_ADMIN
            ]);

            User::create([
                'name' => 'Nikita Kazihin',
                'email' => 'shpakovich_nik@mail.ru',
                'password' => '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy',
                'role_id' => Role::ROLE_ADMIN
            ]);
        }
    }

    public function down()
    {
        //
    }
}
