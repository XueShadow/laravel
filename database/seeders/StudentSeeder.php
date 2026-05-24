<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Create test student user
        $student = User::firstOrCreate(
            ['email' => 'student@school.edu'],
            ['name' => 'Student User', 'password' => Hash::make('student123'), 'role' => 'student']
        );
        $student->assignRole('student');

        // Create student record
        Student::firstOrCreate(
            ['student_number' => 'STU2026001'],
            [
                'user_id' => $student->id,
                'first_name' => 'John',
                'last_name' => 'Student',
                'course' => 'Computer Science',
                'year_level' => 1,
            ]
        );
    }
}
