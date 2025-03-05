<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendEmailNotification;


class ReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookedDates = Reservations::all();
        return view('owner.reservations', compact('reservations'));
    }

    public function getAvalabilities($id) {
        //$bookDates = Reservations::where('annonce_id', $id)->get();
        return response()->json([
            "from" => "2025-03-08",
            "to" => "2025-03-12",
        ]);
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
        //dd("Request Content: ", $request->all());
        $request->validate([
            'annonce_id' => 'required',
            'start' => 'required',
            'end' => 'required',
            'amount' => 'required'
        ]);


        $data = $request->all();
        $data['user_id'] = Auth::id();
        $reservation = Reservations::create($data);
        app(PaymentController::class)->checkout($reservation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservations $reservations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservations $reservations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservations $reservations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservations $reservations)
    {
        //
    }
}
