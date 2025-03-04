<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    public function annonce() {
        return $this->belongsTo(Annonces::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
