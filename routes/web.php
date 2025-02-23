<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

const OWNER_LISTINGS = '/owner/lintings';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Owner Routes
Route::post(OWNER_LISTINGS, []);
Route::get(OWNER_LISTINGS, []);
Route::get(OWNER_LISTINGS . '{id}', []);
Route::put(OWNER_LISTINGS . '{id}', []);
Route::delete(OWNER_LISTINGS . '{id}', []);

// Tourist Routes
Route::get('tourist/listings', []);
Route::get('/tourist/listings/search', []);
Route::post('/tourist/favorites/{listingId}', []);
Route::get('/tourist/favorites', []);
Route::delete('/tourist/favorites/{listingId}', []);

// Admin Routes
Route::get('/admin/listings', []);
Route::delete('/admin/listings/{id}', []);
Route::get('/admin/stats', []);
Route::get('/admin/users', []);
Route::delete('admin/users/{id}', []);


require __DIR__.'/auth.php';
