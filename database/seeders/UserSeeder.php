<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Facundo Valenti',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345'),
            ],
        ]);
    }
}
