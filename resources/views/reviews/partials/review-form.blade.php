<div class="review-form-container" id="reviewForm">
    <form action="{{ isset($review) ? route('reviews.update', $review->id) : route('reviews.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          id="reviewFormElement">
        @csrf
        @if(isset($review))
            @method('PUT')
        @endif
        
        <input type="hidden" name="recipe_id" value="{{ isset($review) ? $review->recipe_id : $recipe->id }}">
        
        <div class="form-group mb-4">
            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
            <div class="rating-container" aria-label="Select a rating from 1 to 5 stars">
                @for($i = 5; $i >= 1; $i--)
                    <input type="radio" 
                           id="star{{ $i }}" 
                           name="rating" 
                           value="{{ $i }}" 
                           {{ (isset($review) && $review->rating == $i) || (isset($draft) && $draft['rating'] == $i) ? 'checked' : '' }}
                           required>
                    <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">{{ $i }}</label>
                @endfor
            </div>
            <div class="quick-guide-tooltip" id="ratingGuide">
                <i class="fas fa-question-circle"></i>
                <div class="tooltip-content">
                    <h5>How to Rate:</h5>
                    <ul>
                        <li>5 stars: Outstanding, exceptional dish</li>
                        <li>4 stars: Great dish, highly recommended</li>
                        <li>3 stars: Good dish with some room for improvement</li>
                        <li>2 stars: Below average, needs improvement</li>
                        <li>1 star: Poor experience, would not recommend</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="form-group mb-4">
            <label for="comment" class="form-label">Your Review <span class="text-danger">*</span></label>
            <textarea 
                name="comment" 
                id="comment" 
                class="form-control @error('comment') is-invalid @enderror" 
                rows="5" 
                placeholder="Share your thoughts about this recipe..." 
                required
                aria-label="Write your review comment here"
                data-suggestion="Try mentioning what you liked most about the recipe"
            >{{ isset($review) ? $review->comment : (isset($draft) ? $draft['comment'] : old('comment')) }}</textarea>
            
            @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            
            <div class="suggestion-bubble" id="commentSuggestion">
                <i class="fas fa-lightbulb"></i> Try mentioning what you liked most about the recipe
            </div>
            
            <div class="draft-info" id="draftStatus">
                @if(isset($draft))
                    <small class="text-muted">Draft restored from {{ $draft['last_saved'] }}</small>
                @else
                    <small class="text-muted">Autosaving...</small>
                @endif
            </div>
        </div>
        
        <div class="form-group mb-4">
            <label for="image" class="form-label d-flex align-items-center">
                <span>Upload Image</span>
                <div class="tooltip-container">
                    <i class="fas fa-info-circle ms-2"></i>
                    <span class="tooltip-text">Attach a photo of your dish (optional)</span>
                </div>
            </label>
            
            <div class="image-upload-container">
                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    class="form-control @error('image') is-invalid @enderror" 
                    accept="image/*"
                    aria-label="Upload a photo of your dish"
                >
                
                @if(isset($review) && $review->image_path)
                    <div class="current-image mt-2">
                        <img src="{{ asset('storage/'.$review->image_path) }}" alt="Current review image" class="img-thumbnail" style="max-height: 150px;">
                        <p class="text-muted small">Current image. Upload a new one to replace it.</p>
                    </div>
                @endif
                
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                <div class="progress mt-2 d-none" id="uploadProgress">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                {{ isset($review) ? 'Update Review' : 'Submit Review' }}
            </button>
            <a href="{{ route('recipes.show', isset($review) ? $review->recipe_id : $recipe->id) }}" class="btn btn-secondary ms-2">//after view recipe screen siap
                Cancel
            </a>
        </div>
    </form>
</div>