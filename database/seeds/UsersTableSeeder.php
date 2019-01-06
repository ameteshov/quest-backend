<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    protected $users = [
        [
            'name' => 'Artem Meteshov',
            'email' => 'meteshov.artem@gmail.com',
            'password' => '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy',
            'role_id' => \App\Model\Role::ROLE_ADMIN
        ],
        [
            'name' => 'Nikita Shpackovich',
            'email' => 'shpakovich_nik@mail.ru',
            'password' => '$2y$10$uv1XO27o56AX8aiMtMZpDuEWnquWrIXjlVpDMjonKNyVAnDd6g1xy',
            'role_id' => \App\Model\Role::ROLE_ADMIN
        ]
    ];
    public function run()
    {
        foreach ($this->users as $user) {
            factory(\App\Model\User::class)->create($user);
        }
    }
}
