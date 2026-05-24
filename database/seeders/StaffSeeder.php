<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::firstOrCreate(
            ['email' => 'staff@school.edu'],
            ['name' => 'Staff User', 'password' => Hash::make('staff123'), 'role' => 'staff']
        );
        $staff->assignRole('staff');
    }
}
