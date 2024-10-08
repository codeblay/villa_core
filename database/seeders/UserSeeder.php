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
        User::query()->create([
            'name'              => 'admin',
            'email'             => 'no-reply@rajavilla.id',
            'email_verified_at' => now(),
            'password'          => 'qweqweqwe',
        ]);
    }
}
