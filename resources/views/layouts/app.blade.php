<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pre-Enrollment System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-white flex flex-col flex-shrink-0">
        <div class="px-6 py-5 border-b border-indigo-700">
            <h1 class="text-xl font-bold tracking-wide">📚 Pre-Enrollment</h1>
            <p class="text-indigo-300 text-xs mt-1">Management System</p>
        </div>
        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            @if(auth()->user()->isAdmin())
                <p class="text-indigo-400 uppercase text-xs font-semibold tracking-wider mt-2 mb-1">Admin</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-4"></i> Dashboard
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('admin.subjects*') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-book w-4"></i> Subjects
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('admin.users*') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-users w-4"></i> Users
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('admin.reports*') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-chart-bar w-4"></i> Reports
                </a>
                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('staff.dashboard') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-clipboard-check w-4"></i> Enrollments
                </a>
            @elseif(auth()->user()->isStaff())
                <p class="text-indigo-400 uppercase text-xs font-semibold tracking-wider mt-2 mb-1">Staff</p>
                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('staff.dashboard') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-clipboard-check w-4"></i> Enrollments
                </a>
            @else
                <p class="text-indigo-400 uppercase text-xs font-semibold tracking-wider mt-2 mb-1">Student</p>
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('student.dashboard') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-home w-4"></i> Dashboard
                </a>
                <a href="{{ route('student.profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 {{ request()->routeIs('student.profile') ? 'bg-indigo-700' : '' }}">
                    <i class="fas fa-user w-4"></i> My Profile
                </a>
            @endif
        </nav>
        <div class="px-4 py-4 border-t border-indigo-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-indigo-400 text-xs capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-indigo-700 text-indigo-200">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top navbar -->
        <header class="bg-white shadow-sm z-10 flex items-center justify-between px-6 py-3">
            <h2 class="text-lg font-semibold text-gray-700">@yield('page-title', 'Dashboard')</h2>
            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <div class="relative" x-data="{ open: false }">
                    <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="relative text-gray-500 hover:text-indigo-600">
                        <i class="fas fa-bell text-xl"></i>
                        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unread > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">{{ $unread }}</span>
                        @endif
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border z-50 max-h-80 overflow-y-auto">
                        <div class="p-3 border-b font-semibold text-sm text-gray-700">Notifications</div>
                        @forelse(auth()->user()->notifications->take(5) as $notification)
                            <div class="px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-indigo-50' }} border-b">
                                <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? '' }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="px-4 py-3 text-sm text-gray-500">No notifications</p>
                        @endforelse
                    </div>
                </div>
                <span class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</span>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-times-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto px-6 py-4">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
