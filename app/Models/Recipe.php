<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'ingredients',
        'instructions',
        'calories_per_serving',
        'servings',
        'cook_time',
        'image_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'calories_per_serving' => 'integer',
        'servings' => 'integer',
        'cook_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the recipe.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviews for the recipe.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
