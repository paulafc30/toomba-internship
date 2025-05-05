<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Laravel\Fortify\TwoFactorAuthenticatable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'string', Rule::in(['client', 'administrator'])],
            'admin_pin' => [
                Rule::requiredIf($request->user_type === 'administrator'),
                'nullable',
                'string',
            ],
        ]);

        // Validar el PIN de administrador si el tipo de usuario es administrador
        if ($request->user_type === 'administrator') {
            $adminPin = $request->input('admin_pin');

            if ($adminPin !== config('app.admin_registration_pin')) {
                return back()->withErrors(['admin_pin' => __('The administrator PIN is incorrect.')])->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        // Habilitar la autenticación en dos factores para el nuevo usuario
        $user->forceFill([
            'two_factor_secret' => encrypt(random_bytes(20)),
            'two_factor_recovery_codes' => encrypt(json_encode(range(1, 8))), // Genera 8 códigos de recuperación
        ])->save();

        event(new Registered($user));

        Auth::login($user);

        // Redirigir a la vista de configuración de la 2FA
        return redirect(route('register.two-factor'));
    }

    /**
     * Show the two-factor authentication setup view after registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showTwoFactorForm(Request $request): View
    {
        $user = Auth::user();

        return view('auth.register-two-factor', [
            'twoFactorSecret' => decrypt($user->two_factor_secret),
            'recoveryCodes' => json_decode(decrypt($user->two_factor_recovery_codes)),
            'qrCode' => $this->generateQrCode($user), // Genera el código QR
        ]);
    }

    /**
     * Generate the QR code SVG.
     *
     * @param  \App\Models\User  $user
     * @return string|null
     */
    protected function generateQrCode(User $user): ?string
    {
        $otpUrl = urlencode(sprintf(
            'otpauth://totp/%s:%s?secret=%s&issuer=%s',
            config('app.name'),
            $user->email,
            decrypt($user->two_factor_secret),
            config('app.name')
        ));

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="#fff" d="M0 0h200v200H0z"/><path fill="#000" d="M40 40h20v20H40zM40 80h20v20H40zM40 120h20v20H40zM40 160h20v20H40zM80 40h20v20H80zM80 160h20v20H80zM120 40h20v20H120zM120 160h20v20H120zM160 40h20v20H160zM160 80h20v20H160zM160 120h20v20H160zM160 160h20v20H160zM60 60h20v20H60zM60 140h20v20H60zM140 60h20v20H140zM140 140h20v20H140zM80 80h20v20H80zM80 120h20v20H80zM120 80h20v20H120zM120 120h20v20H120z"/></svg>
        <script>
            if (typeof QRCode !== \'undefined\') {
                new QRCode(document.querySelector(\'svg\'), \'' . $otpUrl . '\');
            } else {
                console.error(\'QRCode library not loaded.\');
            }
        </script>';
    }

    /**
     * Confirm the two-factor authentication setup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:6', 'min:6'],
        ]);
        
        $user = Auth::user();
        
        if ($user->verifyTwoFactorCode($request->code)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        } else {
            return back()->withErrors(['code' => __('The provided two factor authentication code was invalid.')]);
        }
    }
}