<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'      => Student::count(),
            'total_subjects'      => Subject::count(),
            'pending_enrollments' => Enrollment::where('status', Enrollment::PENDING)->count(),
            'approved_enrollments'=> Enrollment::where('status', Enrollment::APPROVED)->count(),
        ];

        $recentEnrollments = Enrollment::with(['student', 'subjects'])
            ->latest()->take(10)->get();

        $activityLogs = ActivityLog::with('user')
            ->latest()->take(15)->get();

        $monthlyData = Enrollment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')->orderBy('month')
            ->pluck('count', 'month')->toArray();

        $chartData = array_fill(1, 12, 0);
        foreach ($monthlyData as $month => $count) {
            $chartData[$month] = $count;
        }

        $statusData = [
            'pending'   => Enrollment::where('status', 'pending')->count(),
            'approved'  => Enrollment::where('status', 'approved')->count(),
            'rejected'  => Enrollment::where('status', 'rejected')->count(),
            'completed' => Enrollment::where('status', 'completed')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'recentEnrollments', 'activityLogs', 'chartData', 'statusData'));
    }

    public function reports()
    {
        $enrollments = Enrollment::with(['student', 'subjects'])->paginate(20);
        return view('admin.reports', compact('enrollments'));
    }

    public function exportReport()
    {
        $enrollments = Enrollment::with(['student', 'subjects'])->get();
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="enrollments.csv"'];
        $callback = function () use ($enrollments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Student', 'Course', 'Year', 'Academic Year', 'Semester', 'Status', 'Subjects']);
            foreach ($enrollments as $e) {
                fputcsv($file, [
                    $e->id,
                    $e->student->full_name ?? '',
                    $e->student->course ?? '',
                    $e->student->year_level ?? '',
                    $e->academic_year,
                    $e->semester,
                    $e->status,
                    $e->subjects->pluck('code')->join(', '),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
