<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\TemporaryLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        // User management routes
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('/users/{user}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions');
        Route::put('/users/{user}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        // Folder management routes
        Route::resource('/folders', FolderController::class)->except(['show']);
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/edit-folder/{folder}', [FolderController::class, 'editFolder'])->name('folders.edit-folder');
        Route::get('/folders/{folder}/edit', [FolderController::class, 'edit'])->name('folders.edit');
        Route::get('/folders/create', [FolderController::class, 'create'])->name('folders.create');

        // File management routes within folders
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::post('/folders/{folder}/files/upload', [FileController::class, 'storeFile'])->name('folders.files.upload');
        Route::delete('/folders/{folder}/files/{file}', [FileController::class, 'destroyFileInFolder'])->name('folders.files.destroy');

        // Temporary link management routes - MOVED UNDER /temporary-link
        Route::get('/temporary-link', [TemporaryLinkController::class, 'index'])->name('temporary-link.index');
        Route::delete('/temporary-link/{temporaryLink}', [TemporaryLinkController::class, 'destroy'])->name('temporary-link.destroy');
    });

    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::delete('/files/{file}', [FileController::class, 'destroyFile'])->name('files.destroy');
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    });

    // Routes for generating and accessing temporary links (public access)
    Route::post('/files/{file}/generate-temporary-link', [FileController::class, 'generateTemporaryLink'])->name('files.generate-temporary-link');
    Route::get('/temporary-link/{token}', [FileController::class, 'accessTemporaryLink'])->name('temporary-link.access');
    Route::get('/admin/temporary-link/create', [TemporaryLinkController::class, 'createUploadLink'])->name('admin.temporary-link.create');
    Route::post('/admin/temporary-link/upload', [TemporaryLinkController::class, 'storeUploadLink'])
        ->name('admin.temporary-link.store-upload');
    Route::get('/upload/{token}', [TemporaryLinkController::class, 'showTemporaryUploadForm'])->name('temporary-upload.form');
    
});

// Routes outside the authentication middleware and prefixes (keep these if needed)
Route::get('/admin/files', [FileController::class, 'loadView'])->name('admin.files');
Route::post('/upload', [FileController::class, 'uploadStandalone'])->name('upload.standalone');
//Route::get('/download/{file}', [FileController::class, 'dowloadFile'])->name('download');
Route::get('/files/{file}/download', [FileController::class, 'dowloadFile'])->name('files.download');
Route::get('/files', [FileController::class, 'showAllFilesView'])->name('files.view');
Route::delete('/files/{filename}', [FileController::class, 'destroyStandalone'])->name('files.destroy.standalone');

require __DIR__ . '/auth.php';
