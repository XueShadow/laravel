<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Otp;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            ActivityLog::log($user->id, 'login', 'User logged in from ' . $request->ip());

            if ($user->isAdmin()) return redirect()->route('admin.dashboard');
            if ($user->isStaff()) return redirect()->route('staff.dashboard');
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|min:8|confirmed',
            'student_number' => 'required|string|unique:users',
            'course'         => 'required|string',
            'year_level'     => 'required|integer|min:1|max:6',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
        ]);

        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'role'           => 'student',
            'student_number' => $data['student_number'],
            'course'         => $data['course'],
            'year_level'     => $data['year_level'],
        ]);

        $user->assignRole('student');

        Student::create([
            'user_id'        => $user->id,
            'student_number' => $data['student_number'],
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'course'         => $data['course'],
            'year_level'     => $data['year_level'],
        ]);

        ActivityLog::log($user->id, 'register', 'New student registered: ' . $user->email);

        Auth::login($user);
        return redirect()->route('student.dashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        ActivityLog::log($userId, 'logout', 'User logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Otp::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'used'       => false,
        ]);

        Log::channel('single')->info("OTP for {$user->email}: {$code}");

        // Send OTP via email
        Mail::raw(
            "Your password reset OTP code is: {$code}\n\nThis code will expire in 10 minutes.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset OTP - Pre-Enrollment System');
            }
        );

        $request->session()->put('otp_email', $request->email);
        return redirect()->route('otp.verify')->with('success', 'OTP sent to your email!');
    }

    public function showVerifyOtp(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->session()->get('otp_email')]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code'  => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp  = Otp::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'Invalid or expired OTP.']);
        }

        $otp->update(['used' => true]);
        $request->session()->put('reset_user_id', $user->id);
        return redirect()->route('password.reset.form');
    }

    public function showResetPassword(Request $request)
    {
        if (!$request->session()->has('reset_user_id')) {
            return redirect()->route('password.forgot');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $userId = $request->session()->pull('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.forgot')->withErrors(['error' => 'Session expired.']);
        }

        $user = User::findOrFail($userId);
        $user->update(['password' => Hash::make($request->password)]);

        ActivityLog::log($user->id, 'password_reset', 'Password reset via OTP');
        Auth::login($user);

        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isStaff()) return redirect()->route('staff.dashboard');
        return redirect()->route('student.dashboard')->with('success', 'Password reset successful!');
    }
}
