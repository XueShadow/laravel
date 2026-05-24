<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            return redirect()->route('student.profile')->with('warning', 'Please complete your profile first.');
        }

        $enrollments      = $student->enrollments()->with('subjects')->latest()->get();
        $subjects         = Subject::where('is_active', true)->get();
        $activeEnrollment = $student->enrollments()->whereIn('status', ['pending', 'approved'])->latest()->first();

        return view('student.dashboard', compact('student', 'enrollments', 'subjects', 'activeEnrollment'));
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'subject_ids'   => 'required|array|min:1',
            'subject_ids.*' => 'integer|exists:subjects,id',
            'academic_year' => 'required|string',
            'semester'      => 'required|in:1st,2nd,summer',
        ]);

        $student = Auth::user()->student;

        $existing = $student->enrollments()
            ->where('academic_year', $request->academic_year)
            ->where('semester', $request->semester)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'You already have an active enrollment for this period.']);
        }

        $conflicts = Subject::checkConflicts($request->subject_ids);
        if (!empty($conflicts)) {
            return back()->withErrors(['conflicts' => $conflicts]);
        }

        $enrollment = Enrollment::create([
            'student_id'    => $student->id,
            'academic_year' => $request->academic_year,
            'semester'      => $request->semester,
            'status'        => Enrollment::PENDING,
        ]);

        $enrollment->subjects()->attach($request->subject_ids);
        ActivityLog::log(Auth::id(), 'enrollment_submitted', "Student submitted enrollment #{$enrollment->id}");

        return redirect()->route('student.dashboard')->with('success', 'Enrollment submitted successfully!');
    }

    public function profile()
    {
        $student = Auth::user()->student;
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'course'     => 'required|string',
            'year_level' => 'required|integer|min:1|max:6',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
        ]);

        $user->update(['name' => $data['name']]);

        if ($user->student) {
            $user->student->update([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'course'     => $data['course'],
                'year_level' => $data['year_level'],
                'phone'      => $data['phone'],
                'address'    => $data['address'],
            ]);
        } else {
            // Create student record if it doesn't exist
            $studentNumber = $this->generateStudentNumber($data['course'], $data['year_level']);

            $user->student()->create([
                'student_number' => $studentNumber,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'course'     => $data['course'],
                'year_level' => $data['year_level'],
                'phone'      => $data['phone'],
                'address'    => $data['address'],
            ]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Profile updated successfully.');
    }

    private function generateStudentNumber($course, $yearLevel)
    {
        // Generate course code prefix
        $courseCode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $course), 0, 3));

        // Get current year
        $year = date('Y');

        // Get last student number for this course/year
        $lastNumber = \App\Models\Student::where('student_number', 'like', $courseCode . $year . '%')
            ->orderByRaw('CAST(SUBSTRING(student_number, -4) AS UNSIGNED) DESC')
            ->value('student_number');

        if ($lastNumber) {
            // Extract last 4 digits and increment
            $lastDigits = intval(substr($lastNumber, -4));
            $newDigits = str_pad($lastDigits + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start with 0001 for first student
            $newDigits = '0001';
        }

        return $courseCode . $year . $newDigits;
    }
}
