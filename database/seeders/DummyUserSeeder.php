<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        $courses = ['Computer Science', 'Information Technology', 'Business Administration', 'Engineering', 'Nursing'];
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Mary', 'William', 'Patricia', 'Richard', 'Jennifer', 'Charles', 'Linda', 'Joseph', 'Barbara', 'Thomas', 'Susan'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Wilson', 'Anderson', 'Taylor', 'Thomas', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson'];
        
        for ($i = 1; $i <= 200; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $course = $courses[array_rand($courses)];
            $yearLevel = rand(1, 6);
            $studentNumber = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $course), 0, 3)) . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@student.edu'),
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_number' => $studentNumber,
                'course' => $course,
                'year_level' => $yearLevel,
                'phone' => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => 'Address ' . $i . ', City, Province',
            ]);
            
            $user->assignRole('student');
            
            Student::create([
                'user_id' => $user->id,
                'student_number' => $studentNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'course' => $course,
                'year_level' => $yearLevel,
                'phone' => '09' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => 'Address ' . $i . ', City, Province',
            ]);
        }
        
        $this->command->info('200 dummy student users created successfully!');
    }
}
