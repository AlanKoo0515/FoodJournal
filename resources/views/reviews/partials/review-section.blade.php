<div class="reviews-section mt-5" id="reviews">
    <h3>Reviews 
        <span class="badge bg-secondary">{{ $recipe->reviews()->where('draft', false)->count() }}</span>
    </h3>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="average-rating">
            @php
                $avgRating = $recipe->reviews()->where('draft', false)->avg('rating') ?: 0;
                $avgRating = round($avgRating, 1);
            @endphp
            <span class="h4">{{ $avgRating }}</span>
            <div class="rating-display">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($avgRating))
                        <span class="star filled">★</span>
                    @elseif($i - 0.5 <= $avgRating)
                        <span class="star half-filled">★</span>
                    @else
                        <span class="star">★</span>
                    @endif
                @endfor
            </div>
            <span class="text-muted">{{ $recipe->reviews()->where('draft', false)->count() }} {{ Str::plural('review', $recipe->reviews()->where('draft', false)->count()) }}</span>
        </div>
        
        <div>
            <a href="{{ route('reviews.create', $recipe->id) }}" class="btn btn-primary" aria-label="Write a review for this recipe">
                <i class="bi bi-pencil"></i> Write a Review
            </a>
        </div>
    </div>
    
    <div id="top-reviews">
        @forelse($recipe->reviews()->where('draft', false)->orderBy('created_at', 'desc')->limit(3)->get() as $review)
            <div class="review-item mb-4 p-3 border-bottom">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="mb-1">
                            <strong>{{ $review->user->name }}</strong>
                            <span class="text-muted ms-2">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="rating-display">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    
                    @if(Auth::id() == $review->user_id)
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="reviewOptions-{{ $review->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="reviewOptions-{{ $review->id }}">
                            <li><a class="dropdown-item" href="{{ route('reviews.edit', $review->id) }}">Edit</a></li>
                            <li>
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                <div class="review-content mt-2">
                    <p>{{ $review->comment }}</p>
                </div>
                
                @if($review->image_path)
                <div class="review-image mt-3">
                    <img src="{{ Storage::url($review->image_path) }}" alt="Review image" class="img-fluid rounded" style="max-height: 200px;">
                </div>
                @endif
            </div>
        @empty
            <div class="alert alert-info">
                No reviews yet. Be the first to share your experience!
            </div>
        @endforelse
    </div>
    
    @if($recipe->reviews()->where('draft', false)->count() > 3)
        <div class="text-center mt-3">
            <a href="{{ route('reviews.index', $recipe->id) }}" class="btn btn-outline-primary">
                View All Reviews
            </a>
        </div>
    @endif
</div><div class="invalid-feedback" id="rating-error"></div>
                        </div>

                        <!-- Comment textarea -->
                        <div class="form-group mb-3">
                            <label for="comment" class="form-label">Your Review <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="comment" name="comment" rows="6" 
                                placeholder="Share your experience with this recipe..." 
                                aria-describedby="commentHelp"
                            >{{ old('comment', $draft->comment ?? '') }}</textarea>
                            <div class="invalid-feedback" id="comment-error"></div>
                            <small id="commentHelp" class="form-text text-muted">Try mentioning what you liked most about the recipe</small>
                        </div>

                        <!-- Image upload with tooltip -->
                        <div class="form-group mb-3">
                            <label for="image" class="form-label d-block">
                                <span>Photo of your dish</span>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="uploadBtn" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" 
                                    title="Attach a photo of your dish (optional)" 
                                    aria-label="Upload an image of your dish">
                                    <i class="bi bi-upload"></i> Upload Image
                                </button>
                            </label>
                            <input type="file" class="form-control-file d-none" id="image" name="image" accept="image/*">
                            <div class="mt-2" id="imagePreviewContainer" style="display: none;">
                                <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-danger" id="removeImage">Remove</button>
                            </div>
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div id="draft-status" class="text-muted small"></div>
                            <button type="submit" class="btn btn-primary" aria-label="Submit your review">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Image preview functionality
    const uploadBtn = document.getElementById('uploadBtn');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const removeImageBtn = document.getElementById('removeImage');

    uploadBtn.addEventListener('click', function() {
        imageInput.click();
    });

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreviewContainer.style.display = 'none';
    });

    // Form submission with Ajax
    const form = document.getElementById('reviewForm');
    const ratingError = document.getElementById('rating-error');
    const commentError = document.getElementById('comment-error');
    const imageError = document.getElementById('image-error');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset error messages
        ratingError.textContent = '';
        commentError.textContent = '';
        imageError.textContent = '';
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.textContent = data.message;
                form.prepend(alert);

                // Redirect after 2 seconds
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                // Display validation errors
                if (data.errors) {
                    if (data.errors.rating) {
                        document.querySelector('[name="rating"]').classList.add('is-invalid');
                        ratingError.textContent = data.errors.rating[0];
                    }
                    if (data.errors.comment) {
                        document.getElementById('comment').classList.add('is-invalid');
                        commentError.textContent = data.errors.comment[0];
                    }
                    if (data.errors.image) {
                        document.getElementById('image').classList.add('is-invalid');
                        imageError.textContent = data.errors.image[0];
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger';
            alert.textContent = 'There was an issue saving your review. Please try again later.';
            form.prepend(alert);
        });
    });

    // Auto-save draft functionality
    const comment = document.getElementById('comment');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const draftStatus = document.getElementById('draft-status');
    let autoSaveTimeout;

    function saveDraft() {
        const rating = document.querySelector('input[name="rating"]:checked')?.value;
        const commentText = comment.value;
        
        if (!commentText) {
            return; // Don't save empty drafts
        }
        
        const formData = new FormData();
        formData.append('rating', rating || '');
        formData.append('comment', commentText);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("reviews.draft", $recipe->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                draftStatus.textContent = 'Draft saved ' + data.draft_updated_at;
            }
        })
        .catch(error => {
            console.error('Error saving draft:', error);
        });
    }

    // Save draft when user types
    comment.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(saveDraft, 5000); // Save 5s after user stops typing
    });

    // Save draft when rating changes
    ratingInputs.forEach(input => {
        input.addEventListener('change', saveDraft);
    });

    // Save draft every 3 minutes
    setInterval(saveDraft, 180000);

    // Inline suggestion for reviews
    comment.addEventListener('focus', function() {
        if (this.value.length < 10) {
            const suggestions = [
                "Try mentioning what you liked most about the recipe",
                "Consider sharing any modifications you made to the recipe",
                "Did the recipe meet your expectations? Why or why not?"
            ];
            document.getElementById('commentHelp').textContent = suggestions[Math.floor(Math.random() * suggestions.length)];
        }
    });
});
</script>
@endpush

@section('styles')
<style>
/* Rating stars styling */
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.rating input {
    display: none;
}
.rating label {
    color: #ddd;
    font-size: 24px;
    padding: 0 5px;
    cursor: pointer;
}
.rating label:before {
    content: '★';
}
.rating input:checked ~ label {
    color: #ffc107;
}
.rating label:hover,
.rating label:hover ~ label {
    color: #ffdb70;
}
</style>
@endsection