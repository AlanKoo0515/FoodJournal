<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating',
        'comment',
        'image_path',
    ];

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipe that the review is for.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    /**
     * Get the comments for the review.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
    
    /**
     * Calculate average rating for a recipe
     *
     * @param int $recipeId
     * @return float
     */
    public static function getAverageRating($recipeId)
    {
        return self::where('recipe_id', $recipeId)->avg('rating') ?? 0;
    }
    
    /**
     * Count total reviews for a recipe
     *
     * @param int $recipeId
     * @return int
     */
    public static function getReviewCount($recipeId)
    {
        return self::where('recipe_id', $recipeId)->count();
    }
    
    /**
     * Get rating distribution for a recipe
     *
     * @param int $recipeId
     * @return array
     */
    public static function getRatingDistribution($recipeId)
    {
        $distribution = [];
        
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = self::where('recipe_id', $recipeId)
                ->where('rating', $i)
                ->count();
        }
        
        return $distribution;
    }
}
