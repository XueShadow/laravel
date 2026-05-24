@extends('layouts.app')
@section('title','User Details')
@section('page-title','User Details')
@section('content')
<div class="max-w-lg bg-white rounded-xl shadow p-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>
            <span class="px-2 py-1 rounded-full text-xs font-medium mt-1 inline-block
                {{ $user->role=='admin'?'bg-purple-100 text-purple-700':($user->role=='staff'?'bg-blue-100 text-blue-700':'bg-green-100 text-green-700') }}">
                {{ ucfirst($user->role) }}
            </span>
        </div>
    </div>
    @if($user->student)
    <div class="bg-gray-50 rounded-xl p-4 mb-4">
        <h3 class="font-semibold text-gray-700 mb-3">Student Info</h3>
        <dl class="grid grid-cols-2 gap-3 text-sm">
            <div><dt class="text-gray-500">Student #</dt><dd class="font-medium">{{ $user->student->student_number }}</dd></div>
            <div><dt class="text-gray-500">Course</dt><dd class="font-medium">{{ $user->student->course }}</dd></div>
            <div><dt class="text-gray-500">Year Level</dt><dd class="font-medium">{{ $user->student->year_level }}</dd></div>
            <div><dt class="text-gray-500">Full Name</dt><dd class="font-medium">{{ $user->student->full_name }}</dd></div>
        </dl>
    </div>
    @endif
    <div class="flex gap-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">Edit</a>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium">Back</a>
    </div>
</div>
@endsection
