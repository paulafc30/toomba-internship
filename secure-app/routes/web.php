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


// Public routes (without middleware)
Route::get('/admin/files', [FileController::class, 'loadView'])->name('admin.files');
Route::post('/upload', [FileController::class, 'uploadStandalone'])->name('upload.standalone');
Route::get('/files/{file}/download', [FileController::class, 'downloadFile'])->name('files.download');
Route::get('/files', [FileController::class, 'showAllFilesView'])->name('files.view');
Route::delete('/files/{filename}', [FileController::class, 'destroyStandalone'])->name('files.destroy.standalone');
Route::get('/files/view/{file}', [FileController::class, 'viewFile'])->name('files.view');


Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/logout', [AuthController::class, 'logout']);

require __DIR__ . '/auth.php';

// Routes protected by authentication
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   // Administration Panel
    Route::prefix('admin')->name('admin.')->group(function () {

        // User management
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.delete');
        Route::get('/users/{user}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions');
        Route::put('/users/{user}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

       // Folder management
        Route::resource('/folders', FolderController::class)->except(['show']);
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/edit-folder/{folder}', [FolderController::class, 'editFolder'])->name('folders.edit-folder');

        // Files inside folders
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::post('/folders/{folder}/files/upload', [FileController::class, 'storeFile'])->name('folders.files.upload');
        Route::delete('/folders/{folder}/files/{file}', [FileController::class, 'destroyFileInFolder'])->name('folders.files.destroy');

        // Sending temporary links by email
        Route::get('/temporary-link/create', [TemporaryLinkController::class, 'createUpload'])->name('temporary-link.create');
        Route::post('/temporary-link/upload', [TemporaryLinkController::class, 'storeUpload'])->name('temporary-link.store-upload');

        // Managing temporary links
        Route::get('/temporary-link', [TemporaryLinkController::class, 'index'])->name('temporary-link.index');
        Route::delete('/temporary-link/{temporaryLink}', [TemporaryLinkController::class, 'destroy'])->name('temporary-link.destroy');
    });

    // Client Panel
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
        Route::get('/folders/{folder}', [FolderController::class, 'show'])->name('folders.show');
        Route::get('/folders/{folder}/files', [FileController::class, 'index'])->name('folders.files');
        Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    });

    // Access and generation of temporary links
    Route::post('/files/{file}/generate-temporary-link', [FileController::class, 'generateTemporaryLink'])->name('files.generate-temporary-link');
    Route::get('/temporary-link/{token}', [FileController::class, 'accessTemporaryLink'])->name('temporary-link.access');

    // Delete Expired Temporary Users
    Route::get('/delete-expired-temporary-users', [TemporaryLinkController::class, 'deleteExpiredTemporaryUsers'])->name('admin.delete.expired.users');

    Route::get('/phpinfo', function () {
        phpinfo();
    });
});
