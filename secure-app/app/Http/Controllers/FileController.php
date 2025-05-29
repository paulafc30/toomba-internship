<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\TemporaryLink;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Mostrar archivos sin carpeta asignada
    public function showAllFilesView(Request $request)
    {
        $query = $request->input('search');

        $files = File::query()->whereNull('folder_id');

        if ($query) {
            $files = $files->where('name', 'LIKE', '%' . $query . '%');
        }

        $files = $files->get();

        return view('files', compact('files', 'query'));
    }

    // Mostrar archivos de una carpeta concreta
    public function index(Folder $folder, Request $request)
    {
        $query = $request->input('search');

        $filesQuery = $folder->files();

        if ($query) {
            $filesQuery = $filesQuery->where('name', 'LIKE', '%' . $query . '%');
        }

        $files = $filesQuery->get();

        return view('files', compact('files', 'folder', 'query'));
    }

    // Subida de archivo sin carpeta (vista normal)
    public function uploadStandalone(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '_' . time() . '.' . $file->getClientOriginalExtension();

            $relativePath = $file->storeAs('uploads/standalone', $filename, 'public');

            File::create([
                'folder_id' => null,
                'name' => $file->getClientOriginalName(),
                'path' => $relativePath,
                'size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'uploaded_by' => Auth::id(),
            ]);

            return redirect()->route('files.view')->with('success', 'Archivo subido correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al subir archivo standalone: ' . $e->getMessage());
            return redirect()->route('files.view')->with('error', 'Error al subir archivo.');
        }
    }

    // Eliminar archivo standalone
    public function destroyStandalone(string $filename): RedirectResponse
    {
        $fileToDelete = File::where('name', $filename)->whereNull('folder_id')->firstOrFail();
        $filePathInStorage = $fileToDelete->path;

        try {
            if (Storage::disk('public')->exists($filePathInStorage)) {
                Storage::disk('public')->delete($filePathInStorage);
            }

            $fileToDelete->delete();

            return redirect()->route('files.view')->with('success', 'File successfully deleted.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo standalone: ' . $e->getMessage());
            return redirect()->route('files.view')->with('error', 'Error al eliminar archivo.');
        }
    }

    // Subida de archivo a carpeta vía AJAX (JSON)
    public function storeFile(Request $request, Folder $folder): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.'
            ], 401);
        }

        // Comprobamos permiso antes de validar archivo para evitar validación innecesaria
        if ($user->user_type !== 'administrator') {
            $permission = Permission::where('user_id', $user->id)
                ->where('folder_id', $folder->id)
                ->value('permission_type');

            if ($permission !== 'edit') {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para subir archivos a esta carpeta.'
                ], 403);
            }
        }

        try {
            $request->validate([
                'file' => 'required|file|max:10240',
            ]);

            $file = $request->file('file');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '_' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('uploads/' . $folder->id, $filename, 'public');

            File::create([
                'folder_id' => $folder->id,
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Archivo subido correctamente.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al subir archivo a carpeta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el archivo.'
            ], 500);
        }
    }

    // Eliminar archivo dentro de carpeta (web)
    public function destroyFileInFolder(Folder $folder, File $file): RedirectResponse
    {
        if ($file->folder_id !== $folder->id) {
            abort(403, 'Acción no autorizada.');
        }

        if (! $this->userHasAccess($folder)) {
            abort(403, 'Acceso denegado.');
        }

        try {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            $file->delete();

            $this->deleteEmptyStorageFolder($folder->id);

            return back()->with('success', 'File successfully deleted.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo en carpeta: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar archivo.');
        }
    }

    // Eliminar archivo (cliente)
    public function destroyFile(File $file): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || $user->user_type !== 'client') {
            abort(403, 'Acción no autorizada.');
        }

        try {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            $folderId = $file->folder_id;
            $file->delete();

            if ($folderId) {
                $this->deleteEmptyStorageFolder($folderId);
            }

            return back()->with('success', 'File successfully deleted');
        } catch (\Exception $e) {
            Log::error('Error deleting client file: ' . $e->getMessage());
            return back()->with('error', 'Error deleting file');
        }
    }

    // Descargar archivo
    public function downloadFile(File $file): \Symfony\Component\HttpFoundation\StreamedResponse|RedirectResponse
    {
        if (Storage::disk('public')->exists($file->path)) {
            return Storage::disk('public')->download($file->path, $file->name);
        }

        return redirect()->back()->with('error', 'File not found');
    }

    // Generar enlace temporal para archivo
    public function generateTemporaryLink(File $file): RedirectResponse
    {
        try {
            $token = Str::random(60);
            $expiresAt = Carbon::now()->addDay();

            TemporaryLink::create([
                'file_id' => $file->id,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            $url = route('temporary-link.access', ['token' => $token]);
            return back()->with('success', 'Temporary link generated: ' . $url);
        } catch (\Exception $e) {
            Log::error('Error al generar enlace temporal: ' . $e->getMessage());
            return back()->with('error', 'Error al generar enlace temporal.');
        }
    }

    // Acceder a enlace temporal
    public function accessTemporaryLink(string $token): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $temporaryLink = TemporaryLink::where('token', $token)->firstOrFail();

        if (Carbon::parse($temporaryLink->expires_at)->isPast()) {
            $temporaryLink->delete();
            abort(404, 'The temporary link has expired.');
        }

        $file = $temporaryLink->file;

        if (!Storage::disk('public')->exists($file->path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->response($file->path, $file->name, [
            'Content-Disposition' => 'inline; filename="' . $file->name . '"',
        ]);
    }

    // ========
    // UTILIDADES
    // ========

    /**
     * Comprueba si el usuario tiene acceso a la carpeta.
     */
    private function userHasAccess(Folder $folder): bool
    {
        $user = Auth::user();

        if (!$user) return false;

        if ($user->user_type === 'administrator') {
            return true;
        }

        return Permission::where('folder_id', $folder->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Delete the storage folder if it is empty.
     */
    private function deleteEmptyStorageFolder(int $folderId): void
    {
        $folderPath = 'uploads/' . $folderId;

        $files = Storage::disk('public')->files($folderPath);

        if (count($files) === 0) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }
    }
}
