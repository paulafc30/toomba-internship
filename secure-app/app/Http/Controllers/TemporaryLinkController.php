<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\TemporaryLink;
use App\Models\User;
use App\Mail\AccessLinkEmail;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Session; 
class TemporaryLinkController extends Controller
{
    /**
     * Muestra el formulario de envío del enlace.
     */
    public function createUpload()
    {
        return view('admin.create-access-link');
    }

    /**
     * Procesa el formulario y envía el correo con el enlace de subida.
     */
    public function storeUpload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8|max:255',
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

        // Determinar la contraseña para el usuario
        $userPassword = $request->filled('password') ? $request->password : Str::random(16);

        // Crear un usuario temporal
        $temporaryUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($userPassword), // Hashear la contraseña (la proporcionada o la aleatoria)
            'user_type' => 'temporary', // O algún otro identificador para usuarios temporales
            'email_verified_at' => now(), 
        ]);

        // Crear el enlace temporal incluyendo name, email y asociando el ID del usuario
        $temporaryLink = TemporaryLink::create([
            'token' => Str::random(32),
            'name' => $request->name,
            'email' => $request->email,
            'expires_at' => $expiration,
            'password' => $request->filled('password') ? $request->password : $userPassword, // Guardar la contraseña (la proporcionada o la generada)
            'user_id' => $temporaryUser->id, // Asociar el ID del usuario recién creado
        ]);

        // Generar la URL de inicio de sesión
        $accessLink = route('login');

        Mail::to($request->email)->send(
            new AccessLinkEmail($accessLink, $request->name, $temporaryLink, $userPassword, $temporaryUser) // Pasa la contraseña (generada si es necesario)
        );

        // Devolver una respuesta JSON para AJAX
        return response()->json(['success' => 'The link and temporary user have been created and sent successfully.']);
    }

    /**
     * Muestra una vista con el mensaje de éxito y luego redirige al dashboard.
     */
    public function linkSent()
    {
        // Esta ruta ya no es necesaria con la implementación AJAX
        return redirect()->route('dashboard')->with('success', 'The link and temporary user have been created and sent successfully.');
    }

    /**
     * Muestra un listado de los enlaces temporales.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todos los enlaces temporales para mostrar en la vista
        $temporaryLinks = TemporaryLink::all();
        return view('admin.temporary-link', compact('temporaryLinks'));
    }

    /**
     * Elimina un enlace temporal específico.
     *
     * @param  \App\Models\TemporaryLink  $temporaryLink
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TemporaryLink $temporaryLink)
    {
        // Si el enlace tiene un usuario asociado, elimínalo también
        if ($temporaryLink->user) {
            $temporaryLink->user->delete();
        }
        $temporaryLink->delete();
        return redirect()->route('admin.temporary-link.index')->with('success', 'The temporary link and its associated user (if any) have been removed.');
    }

    /**
     * Muestra el formulario de subida temporal.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showTemporaryUploadForm($token)
    {
        $temporaryLink = TemporaryLink::where('token', $token)->firstOrFail();

        // Si el enlace tiene un user_id asociado y ese usuario ya no existe, o si el enlace ha expirado
        if ($temporaryLink->expires_at < now()) {
            //  Si el enlace ha expirado y el usuario asociado no se ha eliminado aún, podrías forzar su eliminación aquí.
            if ($temporaryLink->user) {
                $temporaryLink->user->delete();
            }
            abort(403, 'The upload link has expired.');
        }

        $passwordRequired = !empty($temporaryLink->password);

        return view('temporary-upload-form', [
            'token' => $token,
            'passwordRequired' => $passwordRequired
        ]);
    }

    /**
     * Elimina usuarios temporales expirados.
     * Esta función es redundante si el comando de consola hace el trabajo.
     * Podrías eliminarla o usarla para un trigger manual desde la interfaz de administración.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteExpiredTemporaryUsers()
    {
        return redirect()->back()->with('info', 'Expired temporary user cleanup is handled by the scheduled task.');
    }
}