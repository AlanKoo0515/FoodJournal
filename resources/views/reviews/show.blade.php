@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Review for "{{ $review->recipe->title }}"</h2>
                    <a href="{{ route('recipes.show', $review->recipe_id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Recipe
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <small class="text-muted ml-2">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <p class="review-text">{{ $review->comment }}</p>
                                <small>by {{ $review->user->name }}</small>
                            </div>
                        </div>
                        
                        @if($review->image_path)
                            <div class="review-image mt-3">
                                <img src="{{ asset('storage/'.$review->image_path) }}" 
                                     alt="Review Image" 
                                     class="img-fluid rounded" 
                                     style="max-height: 400px;">
                            </div>
                        @endif
                        
                        @if(Auth::id() == $review->user_id)
                            <div class="mt-4">
                                <a href="{{ route('reviews.edit', $review) }}" class="btn btn-primary" aria-label="Edit this review">
                                    <i class="fas fa-edit"></i> Edit Review
                                </a>
                                <button type="button" 
                                        class="btn btn-danger delete-review" 
                                        data-id="{{ $review->id }}"
                                        aria-label="Delete this review">
                                    <i class="fas fa-trash"></i> Delete Review
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this review? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Delete review click handler
        $('.delete-review').on('click', function() {
            $('#deleteModal').modal('show');
        });
        
        // Confirm delete click handler
        $('#confirmDelete').on('click', function() {
            $.ajax({
                url: '{{ route("reviews.destroy", $review->id) }}',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deleteModal').modal('hide');
                        toastr.success(response.message);
                        
                        // Redirect to recipe page
                        window.location.href = '{{ route("recipes.show", $review->recipe_id) }}';
                    } else {
                        toastr.error('Failed to delete review.');
                    }
                },
                error: function() {
                    $('#deleteModal').modal('hide');
                    toastr.error('There was an error deleting the review. Please try again later.');
                }
            });
        });
    });
</script>
@endsection