@extends('layouts.auth')
@section('title','Login')
@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Sign In</h2>
<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none @error('email') border-red-500 @enderror">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
    </div>
    <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2"><input type="checkbox" name="remember" class="rounded"> Remember me</label>
        <a href="{{ route('password.forgot') }}" class="text-indigo-600 hover:underline">Forgot password?</a>
    </div>
    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">Login</button>
</form>
<p class="text-center text-sm text-gray-600 mt-4">No account? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register</a></p>
@endsection
