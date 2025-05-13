<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\TemporaryLink;
use App\Mail\UploadLinkEmail;

class TemporaryLinkController extends Controller
{
    /**
     * Muestra el formulario de envío del enlace.
     */
    public function createUpload()
    {
        return view('admin.create-upload-link');
    }

    /**
     * Procesa el formulario y envía el correo con el enlace de subida.
     */
    public function storeUpload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'expire_time' => 'nullable|date_format:H:i',
        ]);

        // Calcular la expiración
        $expiration = now()->addDays(7); // Por defecto 7 días

        if ($request->filled('expire_date')) {
            $date = Carbon::parse($request->expire_date);
            $time = $request->filled('expire_time') ? $request->expire_time : '23:59';
            $expiration = Carbon::parse($request->expire_date . ' ' . $time);
        }

        // Crear el enlace temporal
        $temporaryLink = TemporaryLink::create([
            'token' => Str::random(32),
            'expires_at' => $expiration,
            'password' => $request->password,
        ]);

        // Generar la URL del enlace de subida
        $uploadLink = URL::temporarySignedRoute(
            'upload.form', // Asegúrate de tener esta ruta definida
            $temporaryLink->expires_at,
            ['token' => $temporaryLink->token]
        );

        // Enviar el correo
        Mail::to($request->email)->send(
            new UploadLinkEmail($uploadLink, $request->name, $temporaryLink, $request->password)
        );

        return redirect()->route('dashboard')->with('success', 'El enlace de subida ha sido enviado con éxito.');
    }
}
