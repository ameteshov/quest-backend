<?php

use Illuminate\Database\Migrations\Migration;
use App\Model\Role;

class AddRoles extends Migration
{
    protected $roles = ['admin', 'user'];

    public function up()
    {
        foreach ($this->roles as $name) {
            Role::create(['name' => $name]);
        }
    }

    public function down()
    {
        //
    }
}
