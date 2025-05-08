<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes; //not yet

class Review extends Model
{
    use HasFactory, /*SoftDeletes*/;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'comment',
        'rating',
        'image_path'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
