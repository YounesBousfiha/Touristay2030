<?php

use App\Http\Controllers\FavorisController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;
use App\Models\Annonces;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isOwner;
use App\Http\Middleware\isTourist;
use App\Http\Controllers\AnnoncesController;



Route::get('/dashboard', function () {
    $userId = Auth::user()->role_id;
    //dd($userId);
    if($userId === 1) {
        $annoncesCount = Annonces::count();
        $usersCount = User::count();
        return view('admin.adminBoard', compact('annoncesCount', 'usersCount'));
    }  elseif ($userId === 2) {
        return view('owner.ownerBoard');
    } elseif ($userId === 3) {
        return view('tourist.touristBoard');
    } else {
        return redirect('/unauthorized');
    }
})->middleware('auth')->name('dashboard'); // TODO: Add Stats Controller Here


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
Route::get('/owner/property', [AnnoncesController::class, 'GetMyPropertys'])->middleware('auth')->name('owner.myproperty');
Route::post('/owner/property', [AnnoncesController::class, 'store'])->middleware('auth');
Route::get('/owner/property/{id}', []);
Route::post('/owner/updateproperty', [AnnoncesController::class, 'update'])->middleware('auth');
Route::delete('/owner/property/{id}', [AnnoncesController::class, 'destroy'])->middleware('auth');

// Tourist Routes
Route::get('tourist/listings', [AnnoncesController::class, 'index'])->middleware('auth')->name('listings.index');
Route::get('tourist/listings/{id}', [AnnoncesController::class, 'show'])->middleware('auth');
Route::get('/tourist/listings/search', []); // TODO: Create a Smart Search Bar ( Search by City(Location) or by disbonibilité)


Route::post('/tourist/favorites', [FavorisController::class, 'addToFavoris'])->middleware('auth');
Route::get('/tourist/favorites', [FavorisController::class, 'index'])->middleware('auth')->name('favorite.index');
Route::delete('/tourist/favorites/{favorite_id}', [FavorisController::class, 'removeFromFavoris'])->middleware('auth');

// Admin Routes
Route::get('/admin/listings', []);
Route::delete('/admin/listings/{id}', []);
Route::get('/admin/stats', [StatsController::class, 'adminBoard'])->middleware('auth')->name('admin.stats');


require __DIR__.'/auth.php';
