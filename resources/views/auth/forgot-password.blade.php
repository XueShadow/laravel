@extends('layouts.auth')
@section('title','Forgot Password')
@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Forgot Password</h2>
<p class="text-gray-500 text-sm text-center mb-6">Enter your email to receive an OTP code.</p>
<form method="POST" action="{{ route('password.forgot') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
    </div>
    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">Send OTP</button>
</form>
<p class="text-center text-sm text-gray-600 mt-4"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Back to login</a></p>
@endsection
