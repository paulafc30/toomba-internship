<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Redirige al usuario a la página de autenticación de Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Maneja la respuesta de Google después de autenticarse
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar usuario ya registrado por email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Si el usuario no existe en la base de datos, denegar el acceso
                return redirect('/login')->withErrors([
                    'email' => 'Your email is not registered on the platform.'
                ]);
            }

            // Si existe, iniciar sesión
            Auth::login($user);
            return redirect('/dashboard');
        } catch (\Exception $e) {
            // Si ocurre un error, redirigir con mensaje genérico
            return redirect('/login')->withErrors([
                'google' => 'Error authenticating with Google.'
            ]);
        }
    }

    // Cierra la sesión del usuario
    public function logout()
    {

        if (Auth::check()) {
            Auth::user()->update(['twofactor_verified' => false]);
        }

        Auth::logout();
        return redirect('/');
    }
}
