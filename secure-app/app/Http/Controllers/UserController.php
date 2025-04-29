<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Folder;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->user_type === 'administrator') {
            $users = User::paginate(10); // Usa paginación para no cargar todos los usuarios a la vez
            return view('admin.users', compact('users'));
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }

    public function editPermissions(User $user): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->user_type === 'administrator') {
            $folders = Folder::all();

            $userPermissions = Permission::where('user_id', $user->id)
                ->whereNotNull('folder_id')
                ->pluck('folder_id')
                ->toArray();

            return view('admin.edit-permissions', compact('user', 'folders', 'userPermissions'));
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }

    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        if (Auth::check() && Auth::user()->user_type === 'administrator') {
            Permission::where('user_id', $user->id)
                ->whereNotNull('folder_id')
                ->delete();

            if ($request->has('folders')) {
                $folderIds = $request->input('folders');
                foreach ($folderIds as $folderId) {
                    Permission::create([
                        'user_id' => $user->id,
                        'folder_id' => $folderId,
                    ]);
                }
            }

            return redirect()->route('admin.users')->with('success', 'Permissions updated successfully.');
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::check() && Auth::user()->user_type === 'administrator') {
            // Evitar que un administrador se elimine a sí mismo (opcional pero recomendado)
            if ($user->id === Auth::id()) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            // Eliminar permisos asociados al usuario
            Permission::where('user_id', $user->id)->delete();

            $user->delete();

            // Limpiar la contraseña temporal de la sesión si existe
            session()->forget('temporary_user_password_' . $user->id);

            return redirect()->route('admin.users')->with('success', 'User successfully deleted.');
        }

        return redirect()->route('home')->with('error', 'Unauthorized access.');
    }

    public function show(User $user)
    {
        return view('admin.show-info', compact('user'));
    }
}
