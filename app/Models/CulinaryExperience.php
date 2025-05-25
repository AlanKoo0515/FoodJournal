<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulinaryExperience extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'location',
        'date',
        'image_url',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
