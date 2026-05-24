<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            StaffSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
        ]);
    }
}
