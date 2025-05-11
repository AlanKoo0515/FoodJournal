<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating',
        'comment',
        'image_path',
        'draft',
        'draft_updated_at'
    ];

    protected $casts = [
        'rating' => 'integer',
        'draft' => 'boolean',
        'draft_updated_at' => 'datetime',
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
