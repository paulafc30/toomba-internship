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
            $users = User::all();
            return view('admin.users', compact('users')); 
        }

      
        return redirect()->route('home')->with('error', 'Unauthorized access.');
       
    }

    public function editPermissions(User $user)
    {
        $folders = Folder::all(); 

       
        $userPermissions = Permission::where('user_id', $user->id)
            ->whereNotNull('folder_id') 
            ->pluck('folder_id')
            ->toArray();

        return view('admin.edit-permissions', compact('user', 'folders', 'userPermissions'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        
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

    public function destroy(User $user): RedirectResponse
    {
        $user->delete(); 

        return redirect()->route('admin.users')->with('success', 'User successfully deleted.');
    }
}