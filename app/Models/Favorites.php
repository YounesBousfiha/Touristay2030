<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    public function users() {
        return $this->belongsToMany(Favorites::class);
    }
}
