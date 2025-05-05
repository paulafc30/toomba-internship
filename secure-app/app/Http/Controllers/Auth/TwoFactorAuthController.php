<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthController extends Controller
{
    /**
     * Disable two-factor authentication for the authenticated user.
     */
    public function disable(): RedirectResponse
    {
        $user = Auth::user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        return back()->with('status', __('Two-factor authentication has been disabled.'));
    }
}