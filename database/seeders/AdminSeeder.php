<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (app()->environment('production') && (!$email || !$password)) {
            throw new \RuntimeException('ADMIN_EMAIL and ADMIN_PASSWORD must be configured in production.');
        }

        Admin::updateOrCreate(
            ['email' => $email ?: 'admin@laundry.local'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make($password ?: bin2hex(random_bytes(16))),
            ],
        );
    }
}