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
            'annonce_id', 'required|integer',
        ]);

        $userId = Auth::id();

        Cache::remember("favoris_{$userId}", 60, function ()  use ($request){
            return Favorites::create($request->all());
        });

        return view('favoris.index'); // TODO still need to redirect
    }

    public function removeFromFavoris(Request $request) {
        $request->validate([
            'favoris_id', 'required|integer',
        ]);

        $userId = Auth::id();

        $favoris = Favorites::findOrfail($request->input('favoris_id'));
        Cache::forget("favoris_{$userId}");
        $favoris->delete();
        return view('favoris.index')->with('success', 'Favoris Got Deleted');
    }

    public function listAllFavoris() {
        $userId = Auth::id();

        Cache::remember("favoris_{$userId}", 60, function () use($userId) {
            return Favorites::with('users')->where('user_id', $userId)->get;
        });
    }
}
