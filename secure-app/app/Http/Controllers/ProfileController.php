<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Añade validación para la imagen
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Actualiza la imagen de perfil si se proporciona
        if ($request->hasFile('profile_image')) {
            $this->updateProfileImage($request, $user); // Llama a la función separada
        }

        //$user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    private function updateProfileImage(Request $request, User $user): void
    {
        $image = $request->file('profile_image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $path = Storage::putFileAs('public/images', $image, $filename);

        // Elimina la imagen anterior si existe
        if ($user->profile_image_path) {
            Storage::delete('public/' . $user->profile_image_path);
        }

        $user->profile_image_path = 'images/' . $filename;
        $user->save();
    }

    /**
     * Elimina la cuenta del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Elimina la imagen de perfil si existe antes de eliminar el usuario
        if ($user->profile_image_path) {
            Storage::delete('public/' . $user->profile_image_path);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}