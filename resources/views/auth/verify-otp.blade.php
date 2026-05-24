@extends('layouts.auth')
@section('title','Verify OTP')
@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Verify OTP</h2>
<p class="text-gray-500 text-sm text-center mb-6">Enter the 6-digit code sent to your email.</p>
<form method="POST" action="{{ route('otp.verify') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
        <input type="text" name="code" maxlength="6" required placeholder="000000" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-center text-2xl tracking-widest font-mono">
    </div>
    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">Verify OTP</button>
</form>
<p class="text-center text-sm text-gray-600 mt-4"><a href="{{ route('password.forgot') }}" class="text-indigo-600 hover:underline">Resend OTP</a></p>
@endsection
