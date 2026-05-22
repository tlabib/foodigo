<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RiderSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i = 2; $i <= 6; $i++) {
            User::query()->updateOrCreate(
                ['email' => "rider{$i}@gmail.com"],
                [
                    'name' => "rider{$i}",
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_RIDER,
                ]
            );
        }
    }
}
