<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Models\User;
use App\Notifications\SendEmailNotification;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Exception;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function checkout($reservation) {
        $price = $reservation->amount;

        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));

        $stripe->charges->create([
            'amount' => $price * 100,
            'currency' => 'usd',
            'source' => 'tok_visa',
        ]);

        $reservation->status = "paid";
        $reservation->save();
        $user = User::findOrfail($reservation->user_id);

        $user->notify(new SendEmailNotification($reservation));
    }
}
