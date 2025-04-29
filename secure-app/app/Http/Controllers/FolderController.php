<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

// Controller for handling folder-related operations
class FolderController extends Controller
{
    /**
     * Display a listing of the resource (folders).
     * Retrieves folders based on user type (admin or client with permissions).
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $user = Auth::user();
        $folders = collect();

        if ($user && $user->user_type === 'administrator') {
            $folders = Folder::paginate(10);
        } else {
            $userId = Auth::id();
            $folderIds = Permission::where('user_id', $userId)
                ->whereNotNull('folder_id')
                ->pluck('folder_id')
                ->toArray();
            $folders = Folder::whereIn('id', $folderIds)->paginate(10);
        }

        return view('folders', compact('folders', 'user'));
    }

    /**
     * Show the form for creating a new resource (folder).
     * Displays the appropriate creation form based on user type.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        $user = Auth::user();
        if ($user && $user->user_type === 'administrator') {
            return view('admin.create-folder');
        } else {
            return view('client.folders.create');
        }
    }

    /**
     * Store a newly created resource (folder) in storage.
     * Creates a new folder and handles file uploads for clients during creation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255', // Validate folder name
            'files.*' => 'nullable|file|max:2048', // Validate files if uploaded during creation (for clients)
        ]);

        $folder = Folder::create(['name' => $request->name]);

        // Handle file uploads if the user is a client and files are present
        if (Auth::user() && Auth::user()->user_type !== 'administrator' && $request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/' . $folder->id, $filename, 'public');

                File::create([
                    'folder_id' => $folder->id,
                    'name' => $file->getClientOriginalName(),
                    'path' => Storage::url($path),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                ]);
            }
            $redirectRoute = 'client.folders.index';
            $successMessage = __('Folder created successfully.');
        } else {
            $redirectRoute = 'admin.folders.index';
            $successMessage = 'Folder created successfully.';
        }

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * Display the specified resource (folder and its files).
     * Shows the files within a specific folder based on user permissions.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Folder $folder): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, __('Unauthorized.'));
        }

        if ($user->user_type === 'administrator') {
            $files = $folder->files;
            return view('admin.files', compact('files', 'folder'));
        } else {
            $hasPermission = Permission::where('user_id', $user->id)
                ->where('folder_id', $folder->id)
                ->exists();

            if (!$hasPermission) {
                abort(403, __('Unauthorized access to this folder.'));
            }
            $files = File::where('folder_id', $folder->id)->get();
            return view('client.files', compact('folder', 'files'));
        }
    }

    /**
     * Display the files within a specific folder for clients (alternative to show).
     * Ensures the client has permission to view the folder's files.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function files(Folder $folder): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user || $user->user_type === 'administrator') {
            abort(403, __('Unauthorized.'));
        }

        $hasPermission = Permission::where('user_id', $user->id)
            ->where('folder_id', $folder->id)
            ->exists();

        if (!$hasPermission) {
            abort(403, __('Unauthorized access to this folder.'));
        }

        $files = File::where('folder_id', $folder->id)->get();
        return view('client.files', compact('folder', 'files'));
    }

    /**
     * Show the form for editing the specified resource (folder).
     * Displays the appropriate edit form based on user type.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Folder $folder): View
    {
        $user = Auth::user();
        if ($user && $user->user_type === 'administrator') {
            return view('admin.edit-folder', compact('folder'));
        } else {
            return view('client.folders.edit', compact('folder'));
        }
    }

    /**
     * Update the specified resource (folder) in storage.
     * Updates the folder name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Folder $folder): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update($request->all());

        if (Auth::user() && Auth::user()->user_type === 'administrator') {
            $redirectRoute = 'admin.folders.index';
        } else {
            $redirectRoute = 'client.folders.index';
        }
        $successMessage = 'Folder updated successfully.';

        return redirect()->route($redirectRoute)->with('success', $successMessage);
    }

    /**
     * Remove the specified resource (folder) from storage.
     * Deletes the folder and its associated files from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Folder $folder): RedirectResponse
    {
        $user = Auth::user();

        if ($user->user_type !== 'administrator' ) {
            abort(403, __('Unauthorized action.'));
        }

        // Delete associated permissions first
        \App\Models\Permission::where('folder_id', $folder->id)->delete();

        // Get all files associated with this folder
        $filesToDelete = File::where('folder_id', $folder->id)->get();

        // Delete the files from storage and the database
        foreach ($filesToDelete as $file) {
            $filePathInStorage = str_replace(Storage::url(''), '', $file->path);
            if (Storage::disk('public')->exists($filePathInStorage)) {
                Storage::disk('public')->delete($filePathInStorage);
            }
            $file->delete(); // Delete the file record from the database
        }

        // Delete the corresponding uploads subfolder
        $folderPathInStorage = 'uploads/' . $folder->id;
        if (Storage::disk('public')->exists($folderPathInStorage)) {
            Storage::disk('public')->deleteDirectory($folderPathInStorage);
        }

        // Delete the folder from the database
        $folder->delete();

        return redirect()->route('admin.folders.index')->with('success', __('Folder deleted successfully.'));
    }

    /**
     * Check if a folder in storage (within uploads) is empty and delete it.
     *
     * @param  int  $folderId
     * @return void
     */
    public function deleteEmptyStorageFolder(int $folderId): void
    {
        $folderPathInStorage = 'uploads/' . $folderId;
        if (Storage::disk('public')->exists($folderPathInStorage) && empty(Storage::disk('public')->files($folderPathInStorage))) {
            Storage::disk('public')->deleteDirectory($folderPathInStorage);
        }
    }
}