<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $subjects = Subject::all();
        $statuses = ['pending', 'approved', 'rejected'];
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['1st', '2nd', 'summer'];
        
        $enrollmentCount = 0;
        
        foreach ($students as $student) {
            // Create 1-3 enrollments per student
            $numEnrollments = rand(1, 3);
            
            for ($i = 0; $i < $numEnrollments; $i++) {
                $academicYear = $academicYears[array_rand($academicYears)];
                $semester = $semesters[array_rand($semesters)];
                $status = $statuses[array_rand($statuses)];
                
                // Check if enrollment already exists for this period
                $existing = Enrollment::where('student_id', $student->id)
                    ->where('academic_year', $academicYear)
                    ->where('semester', $semester)
                    ->first();
                
                if ($existing) continue;
                
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                    'status' => $status,
                    'notes' => $status === 'rejected' ? 'Incomplete requirements' : ($status === 'approved' ? 'Approved by registrar' : 'Pending review'),
                ]);
                
                // Add 3-6 subjects per enrollment
                $numSubjects = rand(3, 6);
                $selectedSubjects = $subjects->random($numSubjects)->pluck('id');
                $enrollment->subjects()->attach($selectedSubjects);
                
                $enrollmentCount++;
            }
        }
        
        $this->command->info($enrollmentCount . ' dummy enrollments created successfully!');
    }
}
