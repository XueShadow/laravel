<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::firstOrCreate([
            'email' => 'admin@pre-enrollment.com'
        ], [
            'name' => 'System Administrator',
            'password' => \Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $admin->assignRole('admin');

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@pre-enrollment.com');
        $this->command->info('Password: admin123');
    }
}
