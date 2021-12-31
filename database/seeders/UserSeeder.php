<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'adlet assanov',
            'email' => 'adletassanov26@gmail.com',
            'is_manager' => true,
            'password' => bcrypt('123456'),
        ]);
    }
}
