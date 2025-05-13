<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\TemporaryLink;
use App\Mail\AccessLinkEmail;
use Illuminate\Support\Facades\Auth; // Importa la clase Auth

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

        // Crear el enlace temporal (esto podría no ser necesario si solo quieres enviar un enlace de inicio de sesión)
        $temporaryLink = TemporaryLink::create([
            'token' => Str::random(32),
            'expires_at' => $expiration,
            'password' => $request->password,
        ]);

        // Generar la URL de inicio de sesión (o la página de inicio si la autenticación es automática)
        $accessLink = route('login'); // Redirige a la página de inicio de sesión

        // Enviar el correo
        Mail::to($request->email)->send(
            new AccessLinkEmail($accessLink, $request->name, $temporaryLink, $request->password)
        );

        return redirect()->route('dashboard')->with('success', 'The link has been sent successfully.');
    }

    /**
     * Muestra un listado de los enlaces temporales.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Aquí puedes agregar la lógica para obtener y mostrar
        // la lista de enlaces temporales desde tu base de datos.

        // Por ahora, simplemente retornamos una vista (asegúrate de crearla)
        return view('admin.temporary-link');
    }

    /**
     * Elimina un enlace temporal específico.
     *
     * @param  \App\Models\TemporaryLink  $temporaryLink
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TemporaryLink $temporaryLink)
    {
        $temporaryLink->delete();
        return redirect()->route('admin.temporary-link.index')->with('success', 'The temporary link has been removed.');
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

        if ($temporaryLink->expires_at < now()) {
            abort(403, 'The upload link has expired.');
        }

        return view('temporary-upload-form', ['token' => $token, 'passwordRequired' => !empty($temporaryLink->password)]);
    }
    /**
     * Elimina usuarios temporales expirados.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteExpiredTemporaryUsers()
    {
        // Aquí iría la lógica para eliminar usuarios temporales expirados
        // si tu modelo TemporaryLink estuviera relacionado con usuarios.
        // Como no parece ser el caso en este momento, puedes dejarlo vacío
        // o implementar la lógica si la necesitas en el futuro.

        return redirect()->back()->with('info', 'Expired temporary user cleanup is complete.');
    }
}