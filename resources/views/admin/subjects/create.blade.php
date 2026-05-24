@extends('layouts.app')
@section('title','Create Subject')
@section('page-title','Create Subject')
@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow p-6">
    <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Units</label>
                <input type="number" name="units" value="{{ old('units', 3) }}" min="1" max="6" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">
            </div>
            <div class="flex items-center gap-2 mt-5">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm">{{ old('description') }}</textarea>
        </div>
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700">Schedule</label>
                <button type="button" onclick="addSchedule()" class="text-indigo-600 text-sm hover:underline"><i class="fas fa-plus mr-1"></i>Add Slot</button>
            </div>
            <div id="scheduleContainer" class="space-y-2">
                <div class="grid grid-cols-3 gap-2 schedule-row">
                    <select name="schedule[0][day]" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $d)
                        <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                    <input type="time" name="schedule[0][start_time]" value="08:00" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <input type="time" name="schedule[0][end_time]" value="09:30" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-medium text-sm">Create Subject</button>
            <a href="{{ route('admin.subjects.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 font-medium text-sm">Cancel</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
let idx = 1;
function addSchedule() {
    const days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const row = document.createElement('div');
    row.className = 'grid grid-cols-3 gap-2 schedule-row';
    row.innerHTML = `<select name="schedule[${idx}][day]" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">${days.map(d=>`<option value="${d}">${d}</option>`).join('')}</select>
    <input type="time" name="schedule[${idx}][start_time]" value="08:00" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
    <input type="time" name="schedule[${idx}][end_time]" value="09:30" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">`;
    document.getElementById('scheduleContainer').appendChild(row);
    idx++;
}
</script>
@endpush
