<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $fillable = [
        'annonce_id',
        'user_id'
    ];
    public function users() {
        return $this->belongsTo(User::class);
    }

    public function annonces() {
        return $this->belongsTo(Annonces::class);
    }
}
