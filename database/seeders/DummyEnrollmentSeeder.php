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
        $subjects = Subject::all()->pluck('id')->toArray();
        $statuses = ['pending', 'approved', 'rejected'];
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['1st', '2nd', 'summer'];

        $enrollmentCount = 0;
        $enrollmentData = [];
        $enrollmentSubjectData = [];
        $batchSize = 100;
        $subjectBatchSize = 1000;

        $this->command->info('Generating enrollments for 5000 students...');

        // Get all student IDs at once
        $studentIds = Student::pluck('id')->toArray();
        $totalStudents = count($studentIds);

        foreach ($studentIds as $index => $studentId) {
            // Create 2-4 enrollments per student
            $numEnrollments = rand(2, 4);

            for ($i = 0; $i < $numEnrollments; $i++) {
                $academicYear = $academicYears[array_rand($academicYears)];
                $semester = $semesters[array_rand($semesters)];
                $status = $statuses[array_rand($statuses)];

                // Create unique key to avoid duplicates
                $key = $studentId . '_' . $academicYear . '_' . $semester;

                $enrollmentData[$key] = [
                    'student_id' => $studentId,
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                    'status' => $status,
                    'notes' => $status === 'rejected' ? 'Incomplete requirements' : ($status === 'approved' ? 'Approved by registrar' : 'Pending review'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($enrollmentData) >= $batchSize) {
                    DB::table('enrollments')->insert(array_values($enrollmentData));

                    // Get the last inserted IDs and add subjects
                    $lastEnrollments = Enrollment::latest('id')
                        ->limit(count($enrollmentData))
                        ->pluck('id')
                        ->toArray();

                    foreach ($lastEnrollments as $enrollmentId) {
                        $numSubjects = rand(3, 6);
                        $selectedSubjects = array_rand(array_flip($subjects), min($numSubjects, count($subjects)));
                        $selectedSubjects = is_array($selectedSubjects) ? $selectedSubjects : [$selectedSubjects];

                        foreach ($selectedSubjects as $subjectId) {
                            $enrollmentSubjectData[] = [
                                'enrollment_id' => $enrollmentId,
                                'subject_id' => $subjectId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    if (count($enrollmentSubjectData) >= $subjectBatchSize) {
                        DB::table('enrollment_subjects')->insert($enrollmentSubjectData);
                        $enrollmentSubjectData = [];
                    }

                    $enrollmentCount += count($enrollmentData);
                    $enrollmentData = [];
                }
            }

            if ($index % 500 === 0 && $index > 0) {
                $this->command->info("Processed $index students out of $totalStudents...");
            }
        }

        // Insert remaining enrollments
        if (!empty($enrollmentData)) {
            DB::table('enrollments')->insert(array_values($enrollmentData));

            $lastEnrollments = Enrollment::latest('id')
                ->limit(count($enrollmentData))
                ->pluck('id')
                ->toArray();

            foreach ($lastEnrollments as $enrollmentId) {
                $numSubjects = rand(3, 6);
                $selectedSubjects = array_rand(array_flip($subjects), min($numSubjects, count($subjects)));
                $selectedSubjects = is_array($selectedSubjects) ? $selectedSubjects : [$selectedSubjects];

                foreach ($selectedSubjects as $subjectId) {
                    $enrollmentSubjectData[] = [
                        'enrollment_id' => $enrollmentId,
                        'subject_id' => $subjectId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            $enrollmentCount += count($enrollmentData);
        }

        if (!empty($enrollmentSubjectData)) {
            DB::table('enrollment_subjects')->insert($enrollmentSubjectData);
        }

        $this->command->info($enrollmentCount . ' dummy enrollments created successfully!');
    }
}
