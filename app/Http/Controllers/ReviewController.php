<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for a recipe.
     */
    public function index()
    {
        // Get first 10 reviews with lazy loading-paginate
        $reviews = $recipe->reviews()
            ->with('user')
            ->where('draft', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
                    
        return view('reviews.index', compact('recipe', 'reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Recipe $recipe)
    {
        // Check if user has a draft for this recipe
        $draft = Review::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->where('draft', true)
            ->first();
            
        return view('reviews.create', compact('recipe', 'draft'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Recipe $recipe)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review = new Review([
            'recipe_id' => $recipe->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'draft' => false,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $review->image_path = $path;
        }

        $review->save();

        // Delete any drafts
        Review::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->where('draft', true)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully!',
            'redirect' => route('recipes.show', $recipe->id)
        ]);
    }

     /**
     * Save a review draft automatically. //for sqd
     */
    public function saveDraft(Request $request, Recipe $recipe)
    {
        $draft = Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'recipe_id' => $recipe->id,
                'draft' => true,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'draft_updated_at' => now(),
            ]
        );
        // don't handle image uploads in auto-save to keep it light

        return response()->json([
            'success' => true,
            'message' => 'Draft saved',
            'draft_updated_at' => $draft->draft_updated_at->diffForHumans()
        ]);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        // Authorization check
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $recipe = $review->recipe;
        return view('reviews.edit', compact('review', 'recipe'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Authorization check
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $review->rating = $request->rating;
        $review->comment = $request->comment;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image_path) {
                Storage::disk('public')->delete($review->image_path);
            }
            $path = $request->file('image')->store('reviews', 'public');
            $review->image_path = $path;
        }

        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully!',
            'redirect' => route('recipes.show', $review->recipe_id)
        ]);
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        // Authorization check
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }

        $review->delete();

        return redirect()->route('recipes.show', $review->recipe_id)
            ->with('success', 'Review deleted successfully!');
    }

    /**
     * Load more reviews (for lazy loading).
     */
    public function loadMore(Request $request, Recipe $recipe)
    {
        $page = $request->get('page', 1);
        $reviews = $recipe->reviews()
            ->with('user')
            ->where('draft', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
            
        return view('reviews.partials.review-list', compact('reviews'))->render();
    }
}