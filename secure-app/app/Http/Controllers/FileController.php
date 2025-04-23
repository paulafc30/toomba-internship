<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

// Controller for handling file-related operations
class FileController extends Controller
{
    /**
     * Display a view with all standalone files (not associated with a folder).
     */
    public function showAllFilesView(): View
    {
        $files = File::all();
        $folder = null;
        return view('files', compact('files', 'folder'));
    }

    /**
     * Display a view with files belonging to a specific folder.
     */
    public function index(Folder $folder): View
    {
        $files = File::where('folder_id', $folder->id)->get();
        return view('files', compact('folder', 'files'));
    }

    /**
     * Load a view to display all files (standalone).
     */
    public function loadView()
    {
        $files = File::all();
        return view('files', ['files' => $files]);
    }

    /**
     * Upload a standalone file (not associated with a folder).
     */
    public function uploadStandalone(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $ruta = $file->storeAs('', $fileName, 'public'); // Guarda en storage/app/public/$fileName
        File::create([
            'folder_id' => null,
            'name' => $file->getClientOriginalName(),
            'path' => $ruta, // Guarda la ruta relativa: nombre_del_archivo.ext
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        return redirect()->route('files.view')->with('success', 'File uploaded!');
    }


    /**
     * Delete a standalone file from storage and the database.
     */
    public function destroyStandalone(string $filename)
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
     */
    public function storeFile(Request $request, Folder $folder)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $pathRelative = $file->storeAs('uploads/' . $folder->id, $filename, 'public'); // Guarda y obtiene la ruta relativa

            File::create([
                'folder_id' => $folder->id,
                'name' => $file->getClientOriginalName(),
                'path' => $pathRelative, // Usa la ruta relativa directamente
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            return back()->with('success', __('File uploaded successfully to folder: ') . $folder->name);
        }

        return back()->with('error', __('Error uploading file.'));
    }
    /**
     * Delete a file within a specific folder (for administrators).
     */
    public function destroyFileInFolder(Folder $folder, File $file): RedirectResponse
    {
        // Verificar si el archivo pertenece a la carpeta
        if ($file->folder_id !== $folder->id) {
            abort(403, __('Unauthorized action.'));
        }

        try {
            if (Storage::disk('public')->exists(str_replace(Storage::url(''), '', $file->path))) {
                Storage::disk('public')->delete(str_replace(Storage::url(''), '', $file->path));
            }

            $file->delete();

            return back()->with('success', __('File deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with('error', __('Error deleting file: ') . $e->getMessage());
        }
    }

    /**
     * Delete a file (standalone or within a folder) for a client.
     *
     * @param File $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyFile(File $file): RedirectResponse
    {
        if (Auth::user() && Auth::user()->user_type === 'client') {
            // Aquí deberías añadir tu lógica de permisos para verificar
            // si el cliente tiene permiso para eliminar este archivo ($file).
            // Por ejemplo, verificar si el archivo pertenece a una carpeta
            // a la que el cliente tiene acceso.

            try {
                if (Storage::disk('public')->exists(str_replace(Storage::url(''), '', $file->path))) {
                    Storage::disk('public')->delete(str_replace(Storage::url(''), '', $file->path));
                }

                $file->delete();

                return back()->with('success', __('File deleted successfully.'));
            } catch (\Exception $e) {
                return back()->with('error', __('Error deleting file: ') . $e->getMessage());
            }
        } else {
            abort(403, __('Unauthorized action.'));
        }
    }

    public function dowloadFile(File $file)
    {
        $path = Storage::path($file->path);
    
        if (FileFacade::exists($path)) {
            return response()->download($path, $file->name);
        } else {
            return back()->with('error', __('File not found.'));
        }
    }
}
