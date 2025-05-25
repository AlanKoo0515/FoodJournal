<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
            'content' => 'required|string|min:2|max:500',
        ]);
        
        $review = Review::findOrFail($request->review_id);
        
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->review_id = $request->review_id;
        $comment->content = $request->content;
        $comment->save();
        
        return redirect()->back()->with('success', 'Your comment has been posted.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        // Check if the authenticated user owns this comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this comment.');
        }
        
        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if the authenticated user owns this comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this comment.'); //success&failure messages (usability QD)
        }
        
        $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);
        
        $comment->content = $request->content;
        $comment->save();
        
        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        // Check if the authenticated user owns this comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
        
        $comment->delete();
        
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}