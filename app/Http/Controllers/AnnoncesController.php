<?php

namespace App\Http\Controllers;

use App\Models\Annonces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AnnoncesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->validate([
            'per_page' => 'sometimes|integer|min:4|max:20'
        ])['per_page'] ?? 4;

        $listings =  Annonces::paginate($perPage);
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
        //dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'number' => 'required|numeric',
            'location' => 'required',
            'disponibilite' => 'required|date',
            'amenities' => 'nullable|json',
        ]);
        $data = $request->all();
        $data['amenities'] = $request->filled('amenities') ? $request->amenities : null;

        $data['user_id'] = Auth::id();

        //dd($data, $request->all());

        Annonces::create($data);

        Cache::forget('listings');

        return redirect()->route('listings.index')->with('success', 'Annonce created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $announce = Cache::remember("listings_{$id}", 60, function () use ($id) {
           return Annonces::findorfail($id);
        });
        return view('tourist.details', compact('announce'));
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
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'number' => 'required|numeric',
            'disponibilite' => 'required|date',
        ]);

        $annonces = Annonces::findOrfail($request->annonce_id);

        $annonces->update($request->all());
        Cache::forget('listings');

        return back()->with('success', 'Annonce updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
        public function destroy($id)
    {
        $annonces = Annonces::findOrfail($id);
        $annonces->delete();
        Cache::forget('listings');

        return redirect()->route('owner.myproperty')->with('success', 'Annonce deleted successfully.');
    }

    public function GetMyPropertys() {
            $userId = Auth::id();
            $propertys = Annonces::with('users')->where('user_id', $userId)->get();

            return view('owner.myproperty', compact('propertys'));
    }
}
