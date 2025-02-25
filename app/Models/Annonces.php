<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annonces extends Model
{
    use SoftDeletes, HasFactory;
    public function users() {
        return $this->belongsTo(User::class);
    }

    public function favorites() {
        return $this->belongsToMany(Favorites::class, 'favorites');
    }
}
