<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annonces extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'number',
        'location',
        'disponibilite',
        'user_id',
        'amenities'
    ];
    public function users() {
        return $this->belongsTo(User::class);
    }

    public function favorites() {
        return $this->belongsToMany(Favorites::class, 'favorites');
    }

    public function reservations() {
        return $this->hasMany(Reservations::class);
    }
}
