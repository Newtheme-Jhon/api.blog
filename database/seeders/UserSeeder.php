<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Jhon Jairo',
            'email' => 'jhonja14795@gmail.com',
            'password'=> bcrypt('password')
        ]);

        $user->assignRole('admin');

    }
}
