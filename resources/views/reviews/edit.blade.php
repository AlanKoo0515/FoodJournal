@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Edit Your Review for "{{ $recipe->title }}"</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info guide-box mb-4">
                        <h5><i class="fas fa-lightbulb"></i> Quick Guide</h5>
                        <ul>
                            <li>Click on the stars to update your rating</li>
                            <li>Edit your review comment</li>
                            <li>Change or remove the photo if needed</li>
                        </ul>
                    </div>
                    
                    <form id="reviewEditForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="rating">Rating <span class="text-danger">*</span></label>
                            <div class="rating-stars mb-3">
                                <div class="d-flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star-rating" data-rating="{{ $i }}" role="button" aria-label="Rate {{ $i }} out of 5 stars">
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star fa-2x text-warning"></i>
                                        </span>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating" value="{{ $review->rating }}">
                                <div class="invalid-feedback rating-error"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment">Your Review <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="comment" name="comment" rows="5" 
                                  placeholder="Share your experience with this recipe..." 
                                  aria-describedby="commentHelp">{{ $review->comment }}</textarea>
                            <small id="commentHelp" class="form-text text-muted suggestion-text">
                                <i class="fas fa-pen"></i> Try mentioning what you liked most about the recipe
                            </small>
                            <div class="invalid-feedback comment-error"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">
                                <span aria-hidden="true">📷</span> Photo (Optional)
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*"
                                       data-toggle="tooltip" title="Attach a photo of your dish (optional)">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <div class="invalid-feedback image-error"></div>
                            
                            <div class="mt-2" id="imagePreviewContainer" style="{{ $review->image_path ? '' : 'display: none;' }}">
                                <img id="imagePreview" 
                                     src="{{ $review->image_path ? asset('storage/'.$review->image_path) : '#' }}" 
                                     alt="Image Preview" 
                                     class="img-thumbnail mt-2" 
                                     style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-danger ml-2" id="removeImage">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            </div>
                            
                            @if($review->image_path)
                                <input type="hidden" name="keep_existing_image" id="keepExistingImage" value="1">
                            @endif
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary" id="updateReview">
                                <i class="fas fa-save"></i> Update Review
                            </button>
                            <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Star rating system
        $('.star-rating').on('click', function() {
            const rating = $(this).data('rating');
            $('#rating').val(rating);
            
            $('.star-rating i').removeClass('fas').addClass('far');
            $('.star-rating').each(function(index) {
                if (index < rating) {
                    $(this).find('i').removeClass('far').addClass('fas');
                }
            });
            
            // Remove validation error if present
            $('.rating-error').hide();
            $('.star-rating').removeClass('is-invalid');
        });
        
        // Image preview
        $('#image').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#imagePreviewContainer').show();
                    $('.custom-file-label').text(file.name);
                    $('#keepExistingImage').val('0');
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Remove image
        $('#removeImage').on('click', function() {
            $('#image').val('');
            $('#imagePreviewContainer').hide();
            $('.custom-file-label').text('Choose file');
            $('#keepExistingImage').val('0');
        });
        
        // Form submission
        $('#reviewEditForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();
            
            // Create FormData for file upload
            const formData = new FormData(this);
            
            // Validate before submission
            let isValid = true;
            
            if (!$('#rating').val()) {
                $('.rating-error').text('Please select a rating').show();
                $('.star-rating').addClass('is-invalid');
                isValid = false;
            }
            
            if (!$('#comment').val().trim()) {
                $('#comment').addClass('is-invalid');
                $('.comment-error').text('Please write a review comment').show();
                isValid = false;
            }
            
            if (!isValid) {
                return;
            }
            
            // Submit review update
            $.ajax({
                url: '{{ route("reviews.update", $review->id) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#updateReview').prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
                    );
                },
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        // Redirect to recipe page
                        window.location.href = response.redirect;
                    } else {
                        toastr.error('Failed to update review.');
                        $('#updateReview').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Update Review'
                        );
                    }
                },
                error: function(xhr) {
                    $('#updateReview').prop('disabled', false).html(
                        '<i class="fas fa-save"></i> Update Review'
                    );
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        
                        if (errors.rating) {
                            $('.rating-error').text(errors.rating[0]).show();
                            $('.star-rating').addClass('is-invalid');
                        }
                        
                        if (errors.comment) {
                            $('#comment').addClass('is-invalid');
                            $('.comment-error').text(errors.comment[0]).show();
                        }
                        
                        if (errors.image) {
                            $('#image').addClass('is-invalid');
                            $('.image-error').text(errors.image[0]).show();
                        }
                    } else {
                        toastr.error('There was an issue updating your review. Please try again later.');
                    }
                }
            });
        });
    });
</script>
@endsection