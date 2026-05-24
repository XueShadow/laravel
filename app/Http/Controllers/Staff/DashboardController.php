<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Notifications\EnrollmentApproved;
use App\Notifications\EnrollmentRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'subjects']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('course')) {
            $query->whereHas('student', fn($q) => $q->where('course', 'like', '%' . $request->course . '%'));
        }
        if ($request->filled('year_level')) {
            $query->whereHas('student', fn($q) => $q->where('year_level', $request->year_level));
        }

        $enrollments = $query->latest()->paginate(15);
        return view('staff.dashboard', compact('enrollments'));
    }

    public function approve(Request $request, Enrollment $enrollment)
    {
        if (!$enrollment->canApprove()) {
            return back()->withErrors(['error' => 'This enrollment cannot be approved.']);
        }

        $enrollment->update(['status' => Enrollment::APPROVED, 'approved_by' => Auth::id()]);
        ActivityLog::log(Auth::id(), 'enrollment_approved', "Approved enrollment #{$enrollment->id}");
        $enrollment->student->user->notify(new EnrollmentApproved($enrollment));

        return back()->with('success', 'Enrollment approved.');
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!$enrollment->canReject()) {
            return back()->withErrors(['error' => 'This enrollment cannot be rejected.']);
        }

        $enrollment->update([
            'status'      => Enrollment::REJECTED,
            'notes'       => $request->notes,
            'approved_by' => Auth::id(),
        ]);
        ActivityLog::log(Auth::id(), 'enrollment_rejected', "Rejected enrollment #{$enrollment->id}");
        $enrollment->student->user->notify(new EnrollmentRejected($enrollment));

        return back()->with('success', 'Enrollment rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:enrollments,id']);

        $count = 0;
        foreach ($request->ids as $id) {
            $enrollment = Enrollment::find($id);
            if ($enrollment && $enrollment->canApprove()) {
                $enrollment->update(['status' => Enrollment::APPROVED, 'approved_by' => Auth::id()]);
                $enrollment->student->user->notify(new EnrollmentApproved($enrollment));
                $count++;
            }
        }

        ActivityLog::log(Auth::id(), 'bulk_approve', "Bulk approved {$count} enrollments");
        return back()->with('success', "{$count} enrollments approved.");
    }
}
