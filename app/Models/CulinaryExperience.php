<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulinaryExperience extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
