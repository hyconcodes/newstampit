<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && is_null($user->email_verified_at)) {
            return redirect()->route('otp.verify', $user->id);
        }
        return $next($request);
    }
}
