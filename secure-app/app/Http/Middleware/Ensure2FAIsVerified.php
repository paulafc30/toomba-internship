<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Ensure2FAIsVerified
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->twofactor_verified) {
            return redirect()->route('twofactor.verify');
        }

        return $next($request);
    }
}
