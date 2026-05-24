<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Introduction to Computing',
                'code' => 'CS101',
                'units' => 3,
                'schedule' => [['day' => 'Monday', 'start_time' => '08:00', 'end_time' => '09:30'], ['day' => 'Wednesday', 'start_time' => '08:00', 'end_time' => '09:30']],
                'description' => 'Basic computing concepts',
                'is_active' => true,
            ],
            [
                'name' => 'Programming Fundamentals',
                'code' => 'CS102',
                'units' => 3,
                'schedule' => [['day' => 'Tuesday', 'start_time' => '10:00', 'end_time' => '11:30'], ['day' => 'Thursday', 'start_time' => '10:00', 'end_time' => '11:30']],
                'description' => 'Introduction to programming',
                'is_active' => true,
            ],
            [
                'name' => 'Mathematics for Computing',
                'code' => 'MATH101',
                'units' => 3,
                'schedule' => [['day' => 'Monday', 'start_time' => '13:00', 'end_time' => '14:30'], ['day' => 'Friday', 'start_time' => '13:00', 'end_time' => '14:30']],
                'description' => 'Discrete mathematics',
                'is_active' => true,
            ],
            [
                'name' => 'Data Structures',
                'code' => 'CS201',
                'units' => 3,
                'schedule' => [['day' => 'Wednesday', 'start_time' => '10:00', 'end_time' => '11:30'], ['day' => 'Friday', 'start_time' => '10:00', 'end_time' => '11:30']],
                'description' => 'Arrays, linked lists, trees',
                'is_active' => true,
            ],
            [
                'name' => 'Web Development',
                'code' => 'CS301',
                'units' => 3,
                'schedule' => [['day' => 'Tuesday', 'start_time' => '13:00', 'end_time' => '14:30'], ['day' => 'Thursday', 'start_time' => '13:00', 'end_time' => '14:30']],
                'description' => 'HTML, CSS, JavaScript, PHP',
                'is_active' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
