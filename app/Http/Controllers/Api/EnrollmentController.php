<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Notifications\EnrollmentApproved;
use App\Notifications\EnrollmentRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'subjects']);

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('course')) $query->whereHas('student', fn($q) => $q->where('course', $request->course));
        if ($request->filled('year'))   $query->whereHas('student', fn($q) => $q->where('year_level', $request->year));

        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_ids'   => 'required|array',
            'academic_year' => 'required|string',
            'semester'      => 'required|in:1st,2nd,summer',
        ]);

        $student = Auth::user()->student;
        if (!$student) return response()->json(['error' => 'Student profile not found'], 404);

        $conflicts = Subject::checkConflicts($data['subject_ids']);
        if (!empty($conflicts)) return response()->json(['errors' => $conflicts], 422);

        $enrollment = Enrollment::create([
            'student_id'    => $student->id,
            'academic_year' => $data['academic_year'],
            'semester'      => $data['semester'],
            'status'        => Enrollment::PENDING,
        ]);

        $enrollment->subjects()->attach($data['subject_ids']);
        return response()->json($enrollment->load('subjects'), 201);
    }

    public function approve(Enrollment $enrollment)
    {
        if (!$enrollment->canApprove()) {
            return response()->json(['error' => 'Cannot approve this enrollment'], 422);
        }
        $enrollment->update(['status' => Enrollment::APPROVED, 'approved_by' => Auth::id()]);
        $enrollment->student->user->notify(new EnrollmentApproved($enrollment));
        return response()->json($enrollment);
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate(['notes' => 'nullable|string']);
        if (!$enrollment->canReject()) {
            return response()->json(['error' => 'Cannot reject this enrollment'], 422);
        }
        $enrollment->update(['status' => Enrollment::REJECTED, 'notes' => $request->notes, 'approved_by' => Auth::id()]);
        $enrollment->student->user->notify(new EnrollmentRejected($enrollment));
        return response()->json($enrollment);
    }
}
