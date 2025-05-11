@foreach($reviews as $review)
<div class="review-item mb-4 p-3 border-bottom" id="review-{{ $review->id }}">
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
@endforeach

@if($reviews->hasPages())
<div class="d-flex justify-content-center">
    {{ $reviews->links() }}
</div>
@endif