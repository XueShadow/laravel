@extends('layouts.app')
@section('title','Subject Details')
@section('page-title','Subject Details')
@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $subject->name }}</h2>
            <p class="text-indigo-600 font-mono font-medium">{{ $subject->code }}</p>
        </div>
        <span class="px-3 py-1 rounded-full text-sm {{ $subject->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $subject->is_active ? 'Active' : 'Inactive' }}</span>
    </div>
    <dl class="grid grid-cols-2 gap-4 mb-6">
        <div><dt class="text-xs text-gray-500 uppercase tracking-wider">Units</dt><dd class="font-semibold text-gray-800 mt-1">{{ $subject->units }}</dd></div>
        <div><dt class="text-xs text-gray-500 uppercase tracking-wider">Description</dt><dd class="font-semibold text-gray-800 mt-1">{{ $subject->description ?? 'N/A' }}</dd></div>
    </dl>
    <div>
        <h3 class="font-semibold text-gray-700 mb-3">Schedule</h3>
        <div class="space-y-2">
            @foreach($subject->schedule ?? [] as $slot)
            <div class="flex items-center gap-3 bg-indigo-50 px-4 py-2 rounded-lg">
                <i class="fas fa-calendar-day text-indigo-500"></i>
                <span class="font-medium text-gray-700">{{ $slot['day'] }}</span>
                <span class="text-gray-500">{{ $slot['start_time'] }} – {{ $slot['end_time'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="flex gap-3 mt-6">
        <a href="{{ route('admin.subjects.edit', $subject) }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">Edit</a>
        <a href="{{ route('admin.subjects.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium">Back</a>
    </div>
</div>
@endsection
