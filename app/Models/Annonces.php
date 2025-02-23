<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonces extends Model
{
    public function users() {
        return $this->belongsTo(User::class);
    }
}
