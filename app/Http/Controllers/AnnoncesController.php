<?php

namespace App\Http\Controllers;

use App\Models\Annonces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\AnonymousComponent;

class AnnoncesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Cache::remember('listings', 600, function () {
            return Annonces::all();
        });
        return view('tourist.explore', compact('listings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'disponibilite' => 'required|date',
            'amenities' => 'nullable|json',
        ]);

        Annonces::create($request->all());
        Cache::forget('listings');

        return redirect()->route('listings.index')->with('success', 'Annonce created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $announce = Cache::remember("listings_{$id}", 360, function () use ($id) {
           return Annonces::findorfail($id);
        });
        return view('listing.detail', compact('announce'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Annonces $annonces)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Annonces $annonces)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'disponibilite' => 'required|date',
            'amenities' => 'nullable|json',
        ]);

        $annonces->update($request->all());
        Cache::forget('listings');

        return redirect()->route('listings.index')->with('success', 'Annonce updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Annonces $annonces)
    {
        $annonces->delete();
        Cache::forget('listings');

        return redirect()->route('listings.index')->with('success', 'Annonce deleted successfully.');
    }
}
