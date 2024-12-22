<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Mohammad',
            'Store_name' => 'AppStore',
            'phone_number' => '0937689736',
            'password' => bcrypt('1029384756'),
            'role_id' => 1, // Admin
        ]);
    }
}
