<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TemporaryLinkController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


// Rutas públicas (sin middleware)
Route::get('/admin/files', [FileController::class, 'loadView'])->name('admin.files');
Route::post('/upload', [FileController::class, 'uploadStandalone'])->name('upload.standalone');
Route::get('/files/{file}/download', [FileController::class, 'dowloadFile'])->name('files.download');
Route::get('/files', [FileController::class, 'showAllFilesView'])->name('files.view');
Route::delete('/files/{filename}', [FileController::class, 'destroyStandalone'])->name('files.destroy.standalone');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/logout', [AuthController::class, 'logout']);

require __DIR__ . '/auth.php';

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Panel de administración
    Route::prefix('admin')->name('admin.')->group(function () {

        // Gestión de usuarios
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('/users/{user}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions');
        Route::put('/users/{user}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        // Gestión de carpetas
        Route::resource('/folders', FolderController::class)->except(['show']);
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/edit-folder/{folder}', [FolderController::class, 'editFolder'])->name('folders.edit-folder');

        // Archivos dentro de carpetas
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::post('/folders/{folder}/files/upload', [FileController::class, 'storeFile'])->name('folders.files.upload');
        Route::delete('/folders/{folder}/files/{file}', [FileController::class, 'destroyFileInFolder'])->name('folders.files.destroy');

        // Envío de enlaces temporales por correo
        Route::get('/temporary-link/create', [TemporaryLinkController::class, 'createUpload'])->name('temporary-link.create');
        Route::post('/temporary-link/upload', [TemporaryLinkController::class, 'storeUpload'])->name('temporary-link.store-upload');

        // Gestión de enlaces temporales
        Route::get('/temporary-link', [TemporaryLinkController::class, 'index'])->name('temporary-link.index');
        Route::delete('/temporary-link/{temporaryLink}', [TemporaryLinkController::class, 'destroy'])->name('temporary-link.destroy');
    });

    // Panel de cliente
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::delete('/files/{file}', [FileController::class, 'destroyFile'])->name('files.destroy');
    });

    // Acceso y generación de enlaces temporales (públicos firmados)
    Route::post('/files/{file}/generate-temporary-link', [FileController::class, 'generateTemporaryLink'])->name('files.generate-temporary-link');
    Route::get('/temporary-link/{token}', [FileController::class, 'accessTemporaryLink'])->name('temporary-link.access');

    // Tarea administrativa: eliminar usuarios temporales expirados
    Route::get('/delete-expired-temporary-users', [TemporaryLinkController::class, 'deleteExpiredTemporaryUsers'])->name('delete.expired.users');
});