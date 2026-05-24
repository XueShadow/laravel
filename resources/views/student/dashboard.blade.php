@extends('layouts.app')
@section('title','Student Dashboard')
@section('page-title','My Dashboard')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Enrollment Form -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Submit New Enrollment</h3>
        @if($activeEnrollment)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                <i class="fas fa-info-circle mr-1"></i> You have an active enrollment with status: <strong>{{ ucfirst($activeEnrollment->status) }}</strong>
            </div>
        @else
        <form method="POST" action="{{ route('student.enroll') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <input type="text" name="academic_year" value="{{ date('Y') . '-' . (date('Y')+1) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                    <select name="semester" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="1st">1st Semester</option>
                        <option value="2nd">2nd Semester</option>
                        <option value="summer">Summer</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Subjects</label>
                <div class="space-y-2 max-h-48 overflow-y-auto border rounded-lg p-3">
                    @forelse($subjects as $subject)
                    <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                        <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" class="rounded text-indigo-600">
                        <div>
                            <span class="font-medium text-sm text-gray-800">{{ $subject->name }}</span>
                            <span class="text-indigo-600 font-mono text-xs ml-2">{{ $subject->code }}</span>
                            <span class="text-gray-400 text-xs ml-1">{{ $subject->units }} units</span>
                        </div>
                    </label>
                    @empty
                    <p class="text-gray-400 text-sm">No subjects available.</p>
                    @endforelse
                </div>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 text-sm">Submit Enrollment</button>
        </form>
        @endif
    </div>
    <!-- Enrollment History -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Enrollment History</h3>
        <div class="space-y-3">
            @forelse($enrollments as $e)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-medium text-gray-800">{{ $e->semester }} Semester {{ $e->academic_year }}</p>
                        <p class="text-xs text-gray-500">{{ $e->subjects->count() }} subjects enrolled</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $e->status=='approved'?'bg-green-100 text-green-700':($e->status=='pending'?'bg-yellow-100 text-yellow-700':($e->status=='rejected'?'bg-red-100 text-red-700':'bg-blue-100 text-blue-700')) }}">{{ ucfirst($e->status) }}</span>
                </div>
                <div class="flex flex-wrap gap-1">
                    @foreach($e->subjects as $s)
                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-mono">{{ $s->code }}</span>
                    @endforeach
                </div>
                @if($e->notes)
                <p class="text-xs text-red-600 mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $e->notes }}</p>
                @endif
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-8">No enrollment history yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
