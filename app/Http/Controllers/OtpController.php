<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function showVerifyForm(User $user)
    {
        return view('auth.verify-otp', compact('user'));
    }

    public function verify(Request $request, User $user)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otpRecord = DB::table('user_otps')
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$otpRecord || now()->greaterThan($otpRecord->expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new one.']);
        }

        if (!Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        // Mark user as verified
        $user->update(['email_verified_at' => now()]);

        return redirect()->route('student.dashboard')->with('status', 'Your account has been verified!');
    }
}
