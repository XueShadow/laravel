@extends('layouts.app')
@section('title','Subjects')
@section('page-title','Subjects Management')
@section('content')
<div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold text-gray-700">All Subjects</h3>
    <a href="{{ route('admin.subjects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium"><i class="fas fa-plus mr-1"></i> Add Subject</a>
</div>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b"><tr>
            <th class="px-4 py-3 text-left">Code</th>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Units</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Actions</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($subjects as $subject)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-medium text-indigo-600">{{ $subject->code }}</td>
                <td class="px-4 py-3">{{ $subject->name }}</td>
                <td class="px-4 py-3">{{ $subject->units }} units</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs {{ $subject->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.subjects.show', $subject) }}" class="text-blue-600 hover:underline text-xs">View</a>
                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="text-indigo-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">No subjects found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $subjects->links() }}</div>
</div>
@endsection
