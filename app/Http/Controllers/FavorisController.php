<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FavorisController extends Controller
{
    public function addToFavoris(Request $request) {

        $request->validate([
            'announce_id' => 'required|integer',
        ]);
        $userId = Auth::id();

        Favorites::create([
            'annonce_id' => $request->input('announce_id'),
            'user_id' => $userId
        ]);

        return back()->with('success', 'Added to favorites successfully!');
    }

    public function removeFromFavoris(Request $request) {
        $request->validate([
            'favoris_id', 'required|integer',
        ]);

        $userId = Auth::id();

        $favoris = Favorites::findOrfail($request->input('favoris_id'));
        Cache::forget("favoris_{$userId}");
        $favoris->delete();
        return view('favorite.index')->with('success', 'Favoris Got Deleted');
    }

    public function index() {
        $userId = Auth::id();

        $favorites = Cache::remember("favoris_{$userId}", 60, function () use($userId) {
            return Favorites::with('users')->where('user_id', $userId)->get();
        });

        return view('tourist.favoris', compact('favorites'));
    }
}
