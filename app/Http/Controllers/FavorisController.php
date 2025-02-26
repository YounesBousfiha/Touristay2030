<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use Illuminate\Support\Facades\Validator;
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

    public function removeFromFavoris($id) {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required|integer']);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }
        $userId = Auth::id();

        $favoris = Favorites::findOrfail($id)->delete();
        Cache::forget("favoris_{$userId}");
        return back()->with('success', 'Favorite has been deleted !');
    }

    public function index() {
        $userId = Auth::id();
        $favorites = Cache::remember("favoris_{$userId}", 60, function () use($userId) {
            return Favorites::with('annonce')->where('user_id', $userId)->get();
        });

        return view('tourist.favoris', compact('favorites'));
    }
}
