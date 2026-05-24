@extends('layouts.app')
@section('title','Admin Dashboard')
@section('page-title','Admin Dashboard')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-xl"><i class="fas fa-users"></i></div>
        <div><p class="text-gray-500 text-sm">Total Students</p><p class="text-2xl font-bold text-gray-800">{{ $stats['total_students'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-xl"><i class="fas fa-book"></i></div>
        <div><p class="text-gray-500 text-sm">Total Subjects</p><p class="text-2xl font-bold text-gray-800">{{ $stats['total_subjects'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 text-xl"><i class="fas fa-clock"></i></div>
        <div><p class="text-gray-500 text-sm">Pending</p><p class="text-2xl font-bold text-gray-800">{{ $stats['pending_enrollments'] }}</p></div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xl"><i class="fas fa-check-circle"></i></div>
        <div><p class="text-gray-500 text-sm">Approved</p><p class="text-2xl font-bold text-gray-800">{{ $stats['approved_enrollments'] }}</p></div>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Monthly Enrollments ({{ now()->year }})</h3>
        <canvas id="monthlyChart" height="120"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Enrollment Status</h3>
        <canvas id="statusChart" height="120"></canvas>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Recent Enrollments</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left">Student</th><th class="px-3 py-2 text-left">Semester</th><th class="px-3 py-2 text-left">Status</th></tr></thead>
                <tbody class="divide-y">
                    @forelse($recentEnrollments as $e)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">{{ $e->student->full_name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $e->semester }} {{ $e->academic_year }}</td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $e->status=='approved'?'bg-green-100 text-green-700':($e->status=='pending'?'bg-yellow-100 text-yellow-700':($e->status=='rejected'?'bg-red-100 text-red-700':'bg-blue-100 text-blue-700')) }}">
                                {{ ucfirst($e->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400">No enrollments yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
        <h3 class="font-semibold text-gray-700 mb-4">Activity Log</h3>
        <div class="space-y-3 max-h-72 overflow-y-auto">
            @forelse($activityLogs as $log)
            <div class="flex items-start gap-3 text-sm">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 flex-shrink-0 font-bold text-xs">
                    {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $log->user->name ?? 'System' }} <span class="font-normal text-gray-500">{{ $log->action }}</span></p>
                    <p class="text-gray-400 text-xs">{{ $log->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">No activity yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const chartData = @json(array_values($chartData));
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: { labels: months, datasets: [{ label: 'Enrollments', data: chartData, backgroundColor: 'rgba(99,102,241,0.7)', borderRadius: 4 }] },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
const statusData = @json($statusData);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending','Approved','Rejected','Completed'],
        datasets: [{ data: [statusData.pending, statusData.approved, statusData.rejected, statusData.completed],
            backgroundColor: ['#fbbf24','#34d399','#f87171','#60a5fa'] }]
    },
    options: { responsive: true }
});
</script>
@endpush
