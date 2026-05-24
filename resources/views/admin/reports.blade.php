@extends('layouts.app')
@section('title','Reports')
@section('page-title','Enrollment Reports')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold text-gray-700">All Enrollments</h3>
    <a href="{{ route('admin.reports.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium"><i class="fas fa-download mr-1"></i> Export CSV</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b"><tr>
            <th class="px-4 py-3 text-left">Student</th>
            <th class="px-4 py-3 text-left">Course</th>
            <th class="px-4 py-3 text-left">Academic Year</th>
            <th class="px-4 py-3 text-left">Semester</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Subjects</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($enrollments as $e)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $e->student->full_name ?? 'N/A' }}</td>
                <td class="px-4 py-3">{{ $e->student->course ?? '' }}</td>
                <td class="px-4 py-3">{{ $e->academic_year }}</td>
                <td class="px-4 py-3">{{ $e->semester }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $e->status=='approved'?'bg-green-100 text-green-700':($e->status=='pending'?'bg-yellow-100 text-yellow-700':($e->status=='rejected'?'bg-red-100 text-red-700':'bg-blue-100 text-blue-700')) }}">{{ ucfirst($e->status) }}</span></td>
                <td class="px-4 py-3">{{ $e->subjects->pluck('code')->join(', ') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No enrollments found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $enrollments->links() }}</div>
</div>
@endsection
