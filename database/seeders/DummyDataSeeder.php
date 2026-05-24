<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting dummy data generation...');
        
        // Clear existing data (except admins)
        $this->command->info('Clearing existing dummy data...');
        
        // Clear in order to avoid foreign key constraints
        \App\Models\Enrollment::query()->delete();
        \App\Models\ActivityLog::query()->delete();
        \App\Models\Otp::query()->delete();
        \App\Models\Student::query()->delete();
        \App\Models\User::where('role', 'student')->delete();
        \App\Models\Subject::query()->delete();
        
        // Run seeders
        $this->command->info('Creating dummy subjects...');
        $this->call(DummySubjectSeeder::class);
        
        $this->command->info('Creating dummy users and students...');
        $this->call(DummyUserSeeder::class);
        
        $this->command->info('Creating dummy enrollments...');
        $this->call(DummyEnrollmentSeeder::class);
        
        $this->command->info('Creating dummy activity logs...');
        $this->call(DummyActivityLogSeeder::class);
        
        $this->command->info('Creating dummy OTP records...');
        $this->call(DummyOtpSeeder::class);
        
        $this->command->info('Dummy data generation completed successfully!');
    }
}
