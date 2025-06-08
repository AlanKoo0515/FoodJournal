<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of all reviews with filters
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'recipe'])
            ->latest();
        
        // Get filter parameters
        $rating = $request->get('rating');
        $hasImage = $request->get('has_image');
        $ownership = $request->get('ownership', '');
        
        // Apply ownership filter first (affects base query for counts)
        if ($ownership === 'mine' && Auth::check()) {
            $query->where('user_id', Auth::id());
        } elseif ($ownership === 'others' && Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }
        
        // Apply rating filter
        if ($rating && in_array($rating, [1, 2, 3, 4, 5])) {
            $query->where('rating', $rating);
        }
        
        // Apply image filter
        if ($hasImage) {
            $query->whereNotNull('image_path');
        }
        
        // Generate counts for filter buttons (respects ownership filter only)
        $baseQuery = Review::query();
        if ($ownership === 'mine' && Auth::check()) {
            $baseQuery->where('user_id', Auth::id());
        } elseif ($ownership === 'others' && Auth::check()) {
            $baseQuery->where('user_id', '!=', Auth::id());
        }
        
        $counts = [
            'total' => (clone $baseQuery)->count(),
            'with_images' => (clone $baseQuery)->whereNotNull('image_path')->count(),
            '5' => (clone $baseQuery)->where('rating', 5)->count(),
            '4' => (clone $baseQuery)->where('rating', 4)->count(),
            '3' => (clone $baseQuery)->where('rating', 3)->count(),
            '2' => (clone $baseQuery)->where('rating', 2)->count(),
            '1' => (clone $baseQuery)->where('rating', 1)->count(),
        ];
        
        $reviews = $query->paginate(9)->appends($request->all());
        
        return view('reviews.index', compact('reviews', 'counts'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3',
            'image' => 'nullable|image|max:5120',
        ]);
        
        // Check if user already reviewed this recipe
        $existingReview = Review::where('user_id', Auth::id())
            ->where('recipe_id', $request->recipe_id)
            ->first();
            
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this recipe.');
        }
        
        $review = new Review();
        $review->user_id = Auth::id();
        $review->recipe_id = $request->recipe_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('review-images', 'public');
            $review->image_path = $path;
        }
        
        $review->save();
        
        return redirect()->back()->with('success', 'Your review has been posted.');
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        
        // Check if the authenticated user owns this review
        if (Auth::id() !== $review->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this review.');
        }
        
        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Check if the authenticated user owns this review
        if (Auth::id() !== $review->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this review.');
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3',
            'image' => 'nullable|image|max:5120',
        ]);
        
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image 
            if ($review->image_path) {
                Storage::disk('public')->delete($review->image_path);
            }
            
            $path = $request->file('image')->store('review-images', 'public');
            $review->image_path = $path;
        }
        
        // Remove image if requested
        if ($request->has('remove_image') && $review->image_path) {
            Storage::disk('public')->delete($review->image_path);
            $review->image_path = null;
        }
        
        $review->save();
        
        return redirect()->route('recipes.show', $review->recipe_id)
            ->with('success', 'Your review has been updated.');
    }

    public function show(Review $review)
    {
        $comments = $review->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('reviews.show', compact('review', 'comments'));
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Check if the authenticated user owns this review
        if (Auth::id() !== $review->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this review.');
        }
        
        // Delete image 
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }
        
        // Delete comments (should be handled by cascade, but explicit is safer)
        $review->comments()->delete();
        
        // Delete review
        $review->delete();
        
        return redirect()->back()->with('success', 'Your review has been deleted.');
    }
}