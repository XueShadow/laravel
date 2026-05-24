<?php

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/enrollments', [Api\EnrollmentController::class, 'index']);
    Route::post('/enrollments', [Api\EnrollmentController::class, 'store']);
    Route::put('/enrollments/{enrollment}/approve', [Api\EnrollmentController::class, 'approve']);
    Route::put('/enrollments/{enrollment}/reject', [Api\EnrollmentController::class, 'reject']);
    Route::apiResource('/subjects', Api\SubjectController::class);
    Route::get('/notifications', function (Request $request) {
        return $request->user()->notifications()->paginate(10);
    });
    Route::post('/notifications/{id}/read', function (Request $request, $id) {
        $request->user()->notifications()->findOrFail($id)->markAsRead();
        return response()->json(['success' => true]);
    });
    Route::get('/dashboard/stats', function (Request $request) {
        $user = $request->user();
        if ($user->isAdmin()) {
            return response()->json([
                'total_students' => \App\Models\Student::count(),
                'total_subjects' => \App\Models\Subject::count(),
                'pending'        => \App\Models\Enrollment::where('status', 'pending')->count(),
                'approved'       => \App\Models\Enrollment::where('status', 'approved')->count(),
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    });
});
