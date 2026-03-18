<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => env('SUPERADMIN_EMAIL', 'superadmin@kingdombooks.local'),
        ], [
            'first_name' => env('SUPERADMIN_FIRST_NAME', 'Super'),
            'last_name' => env('SUPERADMIN_LAST_NAME', 'Admin'),
            'phone' => env('SUPERADMIN_PHONE', '0000000000'),
            'password' => Hash::make(env('SUPERADMIN_PASSWORD', 'ChangeMe123!')),
            'email_verified_at' => now(),
            'is_superadmin' => true,
            'is_approved' => true,
        ]);
    }
}
