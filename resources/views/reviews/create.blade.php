@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Write a Review for {{ $recipe->title }}</span>
                    <a href="{{ routes('recipes.show', $recipe->id) }}" class="btn btn-sm btn-outline-secondary">Back to Recipe</a>
                </div>

                <div class="card-body">
                    <div class="alert alert-info" id="review-guide" role="alert">
                        <h5>Quick Guide: How to write a great review</h5>
                        <ul>
                            <li>Rate the recipe by clicking on the stars</li>
                            <li>Share your experience preparing and enjoying the dish</li>
                            <li>Upload a photo to show how your dish turned out (optional)</li>
                        </ul>
                        <button type="button" class="btn-close" aria-label="Close guide" onclick="document.getElementById('review-guide').style.display='none'"></button>
                    </div>

                    <form id="reviewForm" method="POST" action="{{ route('reviews.store', $recipe->id) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Rating stars -->
                        <div class="form-group mb-3">
                            <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" {{ old('rating', $draft->rating ?? '') == 5 ? 'checked' : '' }} aria-label="Rate 5 stars"/>
                                <label for="star5" title="5 stars"></label>
                                <input type="radio" id="star4" name="rating" value="4" {{ old('rating', $draft->rating ?? '') == 4 ? 'checked' : '' }} aria-label="Rate 4 stars"/>
                                <label for="star4" title="4 stars"></label>
                                <input type="radio" id="star3" name="rating" value="3" {{ old('rating', $draft->rating ?? '') == 3 ? 'checked' : '' }} aria-label="Rate 3 stars"/>
                                <label for="star3" title="3 stars"></label>
                                <input type="radio" id="star2" name="rating" value="2" {{ old('rating', $draft->rating ?? '') == 2 ? 'checked' : '' }} aria-label="Rate 2 stars"/>
                                <label for="star2" title="2 stars"></label>
                                <input type="radio" id="star1" name="rating" value="1" {{ old('rating', $draft->rating ?? '') == 1 ? 'checked' : '' }} aria-label="Rate 1 star"/>
                                <label for="star1" title="1 star"></label>
                            </div>
                            <div class="invalid-feedback" id="rating-error"></div>
                        </div>

                        <!-- Comment textarea -->
                        <div class="form-group mb-3">
                            <label for="comment" class="form-label">Your Review <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="comment" name="comment" rows="6" 
                                placeholder="Share your experience with this recipe..." 
                                aria-describedby="commentHelp">{{ old('comment', $review->comment) }}</textarea>
                            <div class="invalid-feedback" id="comment-error"></div>
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
                            <div class="mt-2" id="imagePreviewContainer" style="{{ $review->image_path ? '' : 'display: none;' }}">
                                <img id="imagePreview" src="{{ $review->image_path ? Storage::url($review->image_path) : '#' }}" 
                                     alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-danger" id="removeImage">Remove</button>
                            </div>
                            <div class="invalid-feedback" id="image-error"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" aria-label="Update your review">Update Review</button>
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
        // If we're editing and had an existing image, we need to mark it for deletion
        if ('{{ $review->image_path }}') {
            // Add a hidden input to indicate image should be removed
            const removeFlag = document.createElement('input');
            removeFlag.type = 'hidden';
            removeFlag.name = 'remove_image';
            removeFlag.value = '1';
            document.getElementById('editReviewForm').appendChild(removeFlag);
        }
        imagePreviewContainer.style.display = 'none';
    });

    // Form submission with Ajax
    const form = document.getElementById('editReviewForm');
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