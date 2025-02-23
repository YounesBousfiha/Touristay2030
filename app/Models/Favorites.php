<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    public function users() {
        return $this->belongsTo(User::class);
    }

    public function annonces() {
        return $this->belongsTo(Annonces::class);
    }
}
