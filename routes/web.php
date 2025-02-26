<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isOwner;
use App\Http\Middleware\isTourist;
use App\Http\Controllers\AnnoncesController;

const OWNER_LISTINGS = '/owner/listings';


Route::get('/dashboard', function () {
    $userId = Auth::user()->role_id;
    //dd($userId);
    if($userId === 1) {
        return view('admin.adminBoard');
    }  elseif ($userId === 2) {
        //return "Hello Owner";
        return view('owner.ownerBoard');
    } elseif ($userId === 3) {
        //return "Hello Tourist";
        return view('tourist.touristBoard');
    } else {
        return redirect('/unauthorized');
    }
})->middleware('auth')->name('dashboard');


Route::get('/', function () {
    return view('welcome');
})->name('home');

/*Route::get('/send-test-email', function () {
    Mail::to('younesbousfiha96@gmail.com')->send(new TestEmail());
    return response()->json(['message' => 'Test email sent!']);
});*/



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
Route::get('tourist/listings', [AnnoncesController::class, 'index'])->middleware('auth')->name('listings.index');
Route::get('tourist/listings/{id}', [AnnoncesController::class, 'show'])->middleware('auth');
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
