<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class DummySubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['name' => 'Advanced Mathematics', 'code' => 'MATH201', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '09:00', 'end_time' => '10:30'], ['day' => 'Wednesday', 'start_time' => '09:00', 'end_time' => '10:30']], 'description' => 'Advanced mathematical concepts'],
            ['name' => 'Database Systems', 'code' => 'CS202', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '14:00', 'end_time' => '15:30'], ['day' => 'Thursday', 'start_time' => '14:00', 'end_time' => '15:30']], 'description' => 'Database design and management'],
            ['name' => 'Software Engineering', 'code' => 'CS203', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '13:00', 'end_time' => '14:30'], ['day' => 'Friday', 'start_time' => '13:00', 'end_time' => '14:30']], 'description' => 'Software development methodologies'],
            ['name' => 'Computer Networks', 'code' => 'CS204', 'units' => 3, 'schedule' => [['day' => 'Wednesday', 'start_time' => '15:00', 'end_time' => '16:30'], ['day' => 'Friday', 'start_time' => '15:00', 'end_time' => '16:30']], 'description' => 'Network protocols and architectures'],
            ['name' => 'Artificial Intelligence', 'code' => 'CS205', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '09:00', 'end_time' => '10:30'], ['day' => 'Thursday', 'start_time' => '09:00', 'end_time' => '10:30']], 'description' => 'AI and machine learning fundamentals'],
            ['name' => 'Operating Systems', 'code' => 'CS206', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '10:00', 'end_time' => '11:30'], ['day' => 'Wednesday', 'start_time' => '10:00', 'end_time' => '11:30']], 'description' => 'OS concepts and implementation'],
            ['name' => 'Web Applications', 'code' => 'CS207', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '13:00', 'end_time' => '14:30'], ['day' => 'Thursday', 'start_time' => '13:00', 'end_time' => '14:30']], 'description' => 'Modern web application development'],
            ['name' => 'Mobile Development', 'code' => 'CS208', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '15:00', 'end_time' => '16:30'], ['day' => 'Wednesday', 'start_time' => '15:00', 'end_time' => '16:30']], 'description' => 'Mobile app development'],
            ['name' => 'Cybersecurity', 'code' => 'CS209', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '15:00', 'end_time' => '16:30'], ['day' => 'Thursday', 'start_time' => '15:00', 'end_time' => '16:30']], 'description' => 'Security principles and practices'],
            ['name' => 'Cloud Computing', 'code' => 'CS210', 'units' => 3, 'schedule' => [['day' => 'Friday', 'start_time' => '09:00', 'end_time' => '10:30']], 'description' => 'Cloud services and architectures'],
            ['name' => 'Business Finance', 'code' => 'BUS201', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '14:00', 'end_time' => '15:30'], ['day' => 'Wednesday', 'start_time' => '14:00', 'end_time' => '15:30']], 'description' => 'Financial management principles'],
            ['name' => 'Marketing Management', 'code' => 'BUS202', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '10:00', 'end_time' => '11:30'], ['day' => 'Thursday', 'start_time' => '10:00', 'end_time' => '11:30']], 'description' => 'Marketing strategies and tactics'],
            ['name' => 'Human Resources', 'code' => 'BUS203', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '11:00', 'end_time' => '12:30'], ['day' => 'Wednesday', 'start_time' => '11:00', 'end_time' => '12:30']], 'description' => 'HR management and development'],
            ['name' => 'Operations Management', 'code' => 'BUS204', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '14:00', 'end_time' => '15:30'], ['day' => 'Thursday', 'start_time' => '14:00', 'end_time' => '15:30']], 'description' => 'Operations and supply chain'],
            ['name' => 'Business Analytics', 'code' => 'BUS205', 'units' => 3, 'schedule' => [['day' => 'Friday', 'start_time' => '13:00', 'end_time' => '14:30']], 'description' => 'Data analysis for business'],
            ['name' => 'Physics I', 'code' => 'PHY201', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '08:00', 'end_time' => '09:30'], ['day' => 'Wednesday', 'start_time' => '08:00', 'end_time' => '09:30']], 'description' => 'Fundamental physics concepts'],
            ['name' => 'Chemistry I', 'code' => 'CHEM201', 'units' => 3, 'schedule' => [['day' => 'Tuesday', 'start_time' => '08:00', 'end_time' => '09:30'], ['day' => 'Thursday', 'start_time' => '08:00', 'end_time' => '09:30']], 'description' => 'General chemistry principles'],
            ['name' => 'Biology I', 'code' => 'BIO201', 'units' => 3, 'schedule' => [['day' => 'Monday', 'start_time' => '16:00', 'end_time' => '17:30'], ['day' => 'Wednesday', 'start_time' => '16:00', 'end_time' => '17:30']], 'description' => 'Introduction to biology'],
            ['name' => 'Anatomy & Physiology', 'code' => 'NUR201', 'units' => 4, 'schedule' => [['day' => 'Tuesday', 'start_time' => '16:00', 'end_time' => '18:00'], ['day' => 'Thursday', 'start_time' => '16:00', 'end_time' => '18:00']], 'description' => 'Human anatomy and physiology'],
            ['name' => 'Pharmacology', 'code' => 'NUR202', 'units' => 3, 'schedule' => [['day' => 'Friday', 'start_time' => '14:00', 'end_time' => '16:00']], 'description' => 'Drug principles and applications'],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
        
        $this->command->info('20 dummy subjects created successfully!');
    }
}
