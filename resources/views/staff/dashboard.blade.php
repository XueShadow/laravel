@extends('layouts.app')
@section('title','Staff Dashboard')
@section('page-title','Enrollment Management')
@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="p-4 border-b flex gap-3 flex-wrap">
        <form method="GET" class="flex gap-2 flex-wrap">
            <select name="status" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="">All Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
            </select>
            <input type="text" name="course" value="{{ request('course') }}" placeholder="Filter by course" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
            <a href="{{ route('staff.dashboard') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Reset</a>
        </form>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b"><tr>
            <th class="px-4 py-3 text-left">Student</th>
            <th class="px-4 py-3 text-left">Course/Year</th>
            <th class="px-4 py-3 text-left">Period</th>
            <th class="px-4 py-3 text-left">Subjects</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Actions</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($enrollments as $e)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $e->student->full_name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $e->student->course ?? '' }} Y{{ $e->student->year_level ?? '' }}</td>
                <td class="px-4 py-3">{{ $e->semester }} {{ $e->academic_year }}</td>
                <td class="px-4 py-3 text-xs text-gray-600">{{ $e->subjects->pluck('code')->join(', ') }}</td>
                <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $e->status=='approved'?'bg-green-100 text-green-700':($e->status=='pending'?'bg-yellow-100 text-yellow-700':($e->status=='rejected'?'bg-red-100 text-red-700':'bg-blue-100 text-blue-700')) }}">{{ ucfirst($e->status) }}</span></td>
                <td class="px-4 py-3">
                    @if($e->status === 'pending')
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('staff.enrollments.approve', $e) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('staff.enrollments.reject', $e) }}" onsubmit="let n=prompt('Rejection notes (optional):');if(n!==null)this.querySelector('[name=notes]').value=n;else return false;">
                            @csrf @method('PUT')
                            <input type="hidden" name="notes" value="">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Reject</button>
                        </form>
                    </div>
                    @else
                    <span class="text-gray-400 text-xs">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No enrollments found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $enrollments->links() }}</div>
</div>
@endsection
