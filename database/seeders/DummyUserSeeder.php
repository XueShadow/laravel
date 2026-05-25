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
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Mary', 'William', 'Patricia', 'Richard', 'Jennifer', 'Charles', 'Linda', 'Joseph', 'Barbara', 'Thomas', 'Susan', 'Daniel', 'Jessica', 'Matthew', 'Nancy', 'Anthony', 'Karen', 'Mark', 'Lisa', 'Donald', 'Betty'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Wilson', 'Anderson', 'Taylor', 'Thomas', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Young', 'Allen', 'King'];

        $batchSize = 50;
        $processedCount = 0;

        $this->command->info('Generating 5000 dummy student users...');

        for ($batch = 0; $batch < 100; $batch++) {
            $userData = [];
            $studentData = [];

            for ($i = 0; $i < $batchSize; $i++) {
                $counter = ($batch * $batchSize) + $i + 1;
                $firstName = $firstNames[($counter - 1) % count($firstNames)];
                $lastName = $lastNames[($counter - 1) % count($lastNames)];
                $course = $courses[($counter - 1) % count($courses)];
                $yearLevel = (($counter - 1) % 6) + 1;
                $coursePrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $course), 0, 3));
                $studentNumber = $coursePrefix . date('Y') . str_pad($counter, 5, '0', STR_PAD_LEFT);

                $userData[] = [
                    'name' => $firstName . ' ' . $lastName,
                    'email' => strtolower($firstName . '.' . $lastName . $counter . '@student.edu'),
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'student_number' => $studentNumber,
                    'course' => $course,
                    'year_level' => $yearLevel,
                    'phone' => '09' . str_pad($counter * 123456 % 100000000, 8, '0', STR_PAD_LEFT),
                    'address' => 'Address ' . $counter . ', City, Province',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert batch of users
            User::insert($userData);

            // Get the inserted users
            $insertedUsers = User::where('role', 'student')
                ->latest('id')
                ->limit($batchSize)
                ->get();

            // Create corresponding student records
            foreach ($insertedUsers as $user) {
                $nameParts = explode(' ', $user->name);
                $firstName = $nameParts[0] ?? 'First';
                $lastName = isset($nameParts[1]) ? $nameParts[1] : 'Last';

                $studentData[] = [
                    'user_id' => $user->id,
                    'student_number' => $user->student_number,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'course' => $user->course,
                    'year_level' => $user->year_level,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Student::insert($studentData);

            // Assign roles
            $insertedUsers->each(function ($user) {
                $user->assignRole('student');
            });

            $processedCount += $batchSize;
            $this->command->info("Created $processedCount students...");
        }

        $this->command->info('5000 dummy student users created successfully!');
    }
}
