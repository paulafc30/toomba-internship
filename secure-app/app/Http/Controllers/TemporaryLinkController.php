<?php

namespace App\Http\Controllers;

use App\Models\TemporaryLink;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\UploadLinkEmail;
use DateTime;
use DateTimeZone;

class TemporaryLinkController extends Controller
{
    /**
     * Display a listing of all temporary links for administrators.
     */
    public function index()
    {
        $temporaryLinks = TemporaryLink::with('file.owner')->paginate(10);
        return view('admin.temporary-link', compact('temporaryLinks'));
    }

    /**
     * Show the form for creating a new temporary upload link.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function createUploadLink()
    {
        return view('admin.temporary-link.create-upload');
    }

    /**
     * Store a new temporary upload link in the database and create a temporary user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUploadLink(Request $request)
    {
        // 1. Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'nullable|string|min:6',
            'expire_date' => 'nullable|date|after:now',
            'expire_time' => 'nullable|date_format:H:i',
        ]);

        $expiresAtDatabase = null;

        if ($request->filled('expire_date')) {
            $expireDate = $request->input('expire_date');
            $expireTime = $request->input('expire_time', '23:59');

            try {
                $dateTimeString = $expireDate . ' ' . $expireTime;
                $timezone = new DateTimeZone(config('app.timezone'));
                $expiresAt = new DateTime($dateTimeString, $timezone);

                $now = new DateTime('now', $timezone);
                if ($expiresAt <= $now) {
                    return back()->withErrors(['expire_date' => __('The expire date and time must be after the current time.')]);
                }
                $expiresAtDatabase = $expiresAt->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return back()->withErrors(['expire_date' => __('Invalid date or time format.')]);
            }
        } else {
            $now = new DateTime('now', new DateTimeZone(config('app.timezone')));
            $now->modify('+7 days');
            $expiresAtDatabase = $now->format('Y-m-d H:i:s');
        }

        // 2. Verificar si ya existe un registro con el mismo correo y fecha de expiración en temporarylinks
        $existingLink = TemporaryLink::where('email', $request->email)
            ->where('expires_at', $expiresAtDatabase)
            ->first();

        if ($existingLink) {
            return back()->withErrors(['email' => __('A temporary link with this email and expiration time already exists.')]);
        }

        $generatedPassword = null;
        $userPassword = $request->input('password');

        if (!$userPassword) {
            $generatedPassword = Str::random(16);
            $hashedPassword = Hash::make($generatedPassword);
        } else {
            $hashedPassword = Hash::make($userPassword);
        }
        // 3. Generar un token único para el enlace temporal
        $token = Str::random(60);

        // 4. Crear el registro en la tabla temporarylinks
        $temporaryLink = TemporaryLink::create([
            'token' => $token,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $userPassword ? bcrypt($userPassword) : $hashedPassword, // Guarda la contraseña original si se proporcionó, sino la hasheada
            'expires_at' => $expiresAtDatabase,
            // Puedes añadir 'user_id' si quieres rastrear quién creó el enlace:
            // 'user_id' => Auth::id(),
        ]);

        // 5. Crear el nuevo usuario en la tabla users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword,
            'user_type' => 'temporary',
        ]);

        // 6. Almacenar la contraseña sin cifrar en la sesión si se generó
        if ($generatedPassword) {
            session(['temporary_user_password_' . $user->id => $generatedPassword]);
        }

        // 7. Generar la URL de carga temporal
        $uploadLink = route('temporary-upload.form', ['token' => $token]);

        // 8. Enviar el correo electrónico
        Mail::to($request->email)->send(new UploadLinkEmail($uploadLink, $request->name, $temporaryLink));

        // 9. Redirigir con un mensaje de éxito
        return redirect()->route('admin.temporary-link.index')->with('success', __('Temporary upload link and temporary user created successfully and sent to ') . $request->email);
    }

    /**
     * Remove the specified temporary link from storage.
     */
    public function destroy(TemporaryLink $temporaryLink)
    {
        $temporaryLink->delete();
        return back()->with('success', __('Temporary link deleted successfully.'));
    }

    /**
     * Displays the temporary loading form for a given token.
     *
    * @param  string  $token
 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
 */
public function showTemporaryUploadForm(string $token)
{
    $temporaryLink = TemporaryLink::where('token', $token)->firstOrFail();

    $now = new DateTime('now', new DateTimeZone(config('app.timezone')));
    $expiresAt = new DateTime($temporaryLink->expires_at, new DateTimeZone('UTC')); // Asumir que expires_at está en UTC en la BD

    if ($expiresAt <= $now) {
        $temporaryUser = User::where('email', $temporaryLink->email)->where('user_type', 'temporary')->first();

        if ($temporaryUser) {
            \App\Models\Permission::where('user_id', $temporaryUser->id)
                ->whereNotNull('folder_id')
                ->delete();
        }

        return redirect()->route('welcome')->with('error', __('This temporary link has expired and access permissions have been revoked.'));
    }

    return view('admin.temporary-link.upload-form', ['token' => $token, 'temporaryLink' => $temporaryLink]);
}
}
