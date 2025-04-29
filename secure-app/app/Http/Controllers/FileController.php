<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\TemporaryLink;

// Controller for handling file-related operations
class FileController extends Controller
{
    /**
     * Display a view with all standalone files (not associated with a folder).
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showAllFilesView(): View
    {
        $files = File::all();
        $folder = null;
        return view('files', compact('files', 'folder'));
    }

    /**
     * Display a view with files belonging to a specific folder.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Folder $folder): View
    {
        $files = File::where('folder_id', $folder->id)->get();
        return view('files', compact('folder', 'files'));
    }

    /**
     * Load a view to display all files (standalone).
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function loadView(): View
    {
        $files = File::all();
        return view('files', ['files' => $files]);
    }

    /**
     * Upload a standalone file (not associated with a folder).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadStandalone(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $relativePath = $file->storeAs('', $fileName, 'public'); // Store the relative path
        File::create([
            'folder_id' => null,
            'name' => $file->getClientOriginalName(),
            'path' => $relativePath, // Store the relative path
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        return redirect()->route('files.view')->with('success', 'File uploaded!');
    }


    /**
     * Delete a standalone file from storage and the database.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyStandalone(string $filename): RedirectResponse
    {
        $fileToDelete = File::where('name', $filename)
            ->whereNull('folder_id')
            ->firstOrFail();

        $filePathInStorage = str_replace(Storage::url(''), '', $fileToDelete->path);

        try {
            if (Storage::disk('public')->exists($filePathInStorage)) {
                Storage::disk('public')->delete($filePathInStorage);
            }

            $fileToDelete->delete();

            return redirect()->route('files.view')->with('success', 'File successfully deleted.');
        } catch (\Exception $e) {
            return redirect()->route('files.view')->with('error', 'Error deleting file: ' . $e->getMessage());
        }
    }
    /**
     * Store a new file associated with a specific folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFile(Request $request, Folder $folder): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $pathRelative = $file->storeAs('uploads/' . $folder->id, $filename, 'public'); // Store the relative path

            File::create([
                'folder_id' => $folder->id,
                'name' => $file->getClientOriginalName(),
                'path' => $pathRelative, // Store the relative path
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            return back()->with('success', __('File uploaded successfully to folder: ') . $folder->name);
        }

        return back()->with('error', __('Error uploading file.'));
    }
    /**
     * Delete a file within a specific folder (for administrators).
     *
     * @param  \App\Models\Folder  $folder
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFileInFolder(Folder $folder, File $file): RedirectResponse
    {
        // Verify if the file belongs to the folder
        if ($file->folder_id !== $folder->id) {
            abort(403, __('Unauthorized action.'));
        }

        try {
            if (Storage::disk('public')->exists(str_replace(Storage::url(''), '', $file->path))) {
                Storage::disk('public')->delete(str_replace(Storage::url(''), '', $file->path));
            }

            $file->delete();

            // Check if the folder is now empty and delete it from storage
            $folderController = new FolderController();
            $folderController->deleteEmptyStorageFolder($folder->id);

            return back()->with('success', __('File deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Error deleting file: ') . $e->getMessage());
        }
    }

    /**
     * Delete a file (standalone or within a folder) for a client.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFile(File $file): RedirectResponse
    {
        if (Auth::user() && Auth::user()->user_type === 'client') {
            // Here you should add your permission logic to verify
            // if the client has permission to delete this file ($file).
            // For example, verify if the file belongs to a folder
            // that the client has access to.

            try {
                if (Storage::disk('public')->exists(str_replace(Storage::url(''), '', $file->path))) {
                    Storage::disk('public')->delete(str_replace(Storage::url(''), '', $file->path));
                }

                $folderId = $file->folder_id;
                $file->delete();

                // Check if the folder is now empty and delete it from storage (if the file was in a folder)
                if ($folderId) {
                    $folderController = new FolderController();
                    $folderController->deleteEmptyStorageFolder($folderId);
                }

                return back()->with('success', __('File deleted successfully.'));
            } catch (\Exception $e) {
                return back()->with('error', __('Error deleting file: ') . $e->getMessage());
            }
        } else {
            abort(403, __('Unauthorized action.'));
        }
    }

    /**
     * Download a specific file.
     *
     * @param  \App\Models\File  $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function dowloadFile(File $file)
    {
        if (Storage::disk('public')->exists($file->path)) {
            return Storage::disk('public')->download($file->path, $file->name);
        } else {
            return back()->with('error', __('File not found.'));
        }
    }

    /**
     * Generate a temporary link for a specific file.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateTemporaryLink(File $file): RedirectResponse
    {
        $token = Str::random(60);
        $expiresTimestamp = time() + (24 * 3600); // Expiration in 24 hours (in seconds)
        $expiresAt = date('Y-m-d H:i:s', $expiresTimestamp); // Format for the database

        $temporaryLink = TemporaryLink::create([
            'file_id' => $file->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $temporaryLinkUrl = route('temporary-link.access', ['token' => $token]);

        return back()->with('success', __('Temporary link generated: ') . $temporaryLinkUrl);
    }

    /**
     * Access a file via a temporary link.
     *
     * @param  string  $token
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function accessTemporaryLink(string $token)
    {
        $temporaryLink = TemporaryLink::where('token', $token)->firstOrFail();

        if ($temporaryLink->expires_at) {
            $expirationTimestamp = strtotime($temporaryLink->expires_at);
            if (time() > $expirationTimestamp) {
                $temporaryLink->delete();
                abort(404, __('Temporary link has expired.'));
            }
        }

        $file = $temporaryLink->file;
        $filePath = Storage::path($file->path);
        $fileName = $file->name;

        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
}