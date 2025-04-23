<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Authenticated user profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Administration routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // User management routes
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('/users/{user}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions');
        Route::put('/users/{user}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');

        // Folder management routes (admin)
        Route::resource('folders', FolderController::class)->except(['show']);
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/edit-folder/{folder}', [FolderController::class, 'editFolder'])->name('folders.edit-folder');
        Route::resource('folders', FolderController::class)->except(['edit']);
        Route::get('/folders/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');
        Route::get('/folders/create', [FolderController::class, 'create'])->name('folders.create');

        // File management routes within folders (admin)
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::post('/folders/{folder}/files/upload', [FileController::class, 'storeFile'])->name('folders.files.upload');
        Route::delete('/folders/{folder}/files/{file}', [FileController::class, 'destroyFileInFolder'])->name('folders.files.destroy');
    });

    // Client routes
    Route::get('/client/folders/{folder}', [FolderController::class, 'show'])->name('client.folders.show');
    Route::get('/client/folders/{folder}/files', [FileController::class, 'index'])->name('client.folders.files');
    Route::delete('/client/files/{file}', [FileController::class, 'destroyFile'])->name('client.files.destroy');
    Route::get('/client/folders', [App\Http\Controllers\FolderController::class, 'index'])->name('client.folders.index');
});

// Routes outside the authentication middleware and prefixes
Route::get('/admin/files', [FileController::class, 'loadView'])->name('admin.files');
Route::post('/upload', [FileController::class, 'uploadStandalone'])->name('upload.standalone');
Route::get('/download/{file}', [FileController::class, 'dowloadFile'])->name('download');

// General file management routes
Route::get('/files', [FileController::class, 'showAllFilesView'])->name('files.view');
Route::delete('/files/{filename}', [FileController::class, 'destroyStandalone'])->name('files.destroy.standalone');

require __DIR__ . '/auth.php';