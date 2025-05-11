@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Reviews for {{ $recipe->title }}</span>
                    <div>
                        <a href="{{ route('recipes.show', $recipe->id) }}" class="btn btn-sm btn-outline-secondary me-2">Back to Recipe</a>
                        <a href="{{ route('reviews.create', $recipe->id) }}" class="btn btn-sm btn-primary">Write a Review</a>
                    </div>
                </div>

                <div class="card-body">
                    <div id="reviews-container">
                        @if($reviews->count() > 0)
                            @include('reviews.partials.review-list', ['reviews' => $reviews])
                        @else
                            <div class="alert alert-info">
                                No reviews yet. Be the first to share your experience!
                            </div>
                        @endif
                    </div>
                    
                    @if($reviews->hasMorePages())
                    <div class="text-center mt-4">
                        <button id="load-more" class="btn btn-outline-primary" 
                                data-next-page="{{ $reviews->currentPage() + 1 }}"
                                data-recipe-id="{{ $recipe->id }}">
                            Load More Reviews
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const nextPage = this.dataset.nextPage;
            const recipeId = this.dataset.recipeId;
            
            // Show loading indicator
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            this.disabled = true;
            
            fetch(`/recipes/${recipeId}/reviews/load-more?page=${nextPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Append new reviews
                document.getElementById('reviews-container').insertAdjacentHTML('beforeend', html);
                
                // Update button state
                const reviewsContainer = document.createRange().createContextualFragment(html);
                const hasPagination = reviewsContainer.querySelector('.pagination');
                
                if (hasPagination) {
                    this.dataset.nextPage = parseInt(nextPage) + 1;
                    this.innerHTML = 'Load More Reviews';
                    this.disabled = false;
                } else {
                    this.remove(); // No more pages, remove button
                }
            })
            .catch(error => {
                console.error('Error loading more reviews:', error);
                this.innerHTML = 'Error loading reviews. Try again.';
                this.disabled = false;
            });
        });
    }
});
</script>
@endpush

@section('styles')
<style>
.rating-display .star {
    color: #ddd;
    font-size: 18px;
}
.rating-display .star.filled {
    color: #ffc107;
}
</style>
@endsection