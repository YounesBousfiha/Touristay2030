<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $fillable = [
        'annonce_id',
        'user_id'
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function annonce() {
        return $this->belongsTo(Annonces::class);
    }
}
