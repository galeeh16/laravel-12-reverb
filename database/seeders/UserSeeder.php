<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');
        $password = Hash::make('Secret12345');

        $records = [
            [
                'name' => 'Galih Anggoro Jati',
                'email' => 'galih@example.com',
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ratih Esti Hapsari',
                'email' => 'ratih@example.com',
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Whenni Lya Anggraeni',
                'email' => 'whenni@example.com',
                'password' => $password,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        User::insert($records);
    }
}
