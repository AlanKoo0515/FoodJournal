<section id="reviews" class="mt-12">
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 font-inter">
                Reviews ({{ $reviews->total() }})
            </h2>
            
            <button id="add-review-button" class="px-4 py-2 font-semibold text-white transition-all duration-200 bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:shadow-lg font-inter">
                Add Review
            </button>
        </div>

        <!-- Add Review Form (Initially Hidden) -->
        <div id="review-form-container" class="hidden p-6 mb-8 border border-gray-200 shadow-sm bg-gradient-to-br from-gray-50 to-white rounded-xl">
            <h3 class="mb-4 text-xl font-bold text-gray-800 font-inter">Write a Review</h3>
            
            <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Hidden Recipe ID -->
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                <!-- Rating -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 font-inter">Rating</label>
                    <div class="flex items-center">
                        <div class="flex space-x-1" id="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" data-rating="{{ $i }}" class="w-10 h-10 text-gray-300 transition-colors duration-200 star-btn hover:text-yellow-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-value" value="{{ old('rating', 0) }}">
                    </div>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-500 font-inter">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 font-inter">Review Comment</label>
                    <textarea id="comment" name="comment" rows="5" class="block w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50 font-inter" placeholder="Share your experience with this recipe">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-500 font-inter">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 font-inter">Upload a photo of your dish (Optional)</label>
                    <div class="flex items-center mt-1">
                        <input type="file" id="image" name="image" accept="image/*" class="block w-full mt-1 text-sm text-gray-500 font-inter file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 file:font-inter">
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-500 font-inter">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end">
                    <button type="button" id="cancel-review-button" class="px-4 py-2 mr-2 font-semibold text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg shadow-sm hover:bg-gray-200 hover:shadow-md font-inter">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 font-semibold text-white transition-all duration-200 bg-orange-500 rounded-lg shadow-md hover:bg-orange-600 hover:shadow-lg font-inter" data-toggle="tooltip" title="Click to submit review">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>

        <!-- Reviews List -->
        @forelse ($reviews as $review)
            <div class="p-6 mb-6 transition-shadow duration-300 border border-gray-100 shadow-md bg-gradient-to-br from-slate-50 to-blue-50 rounded-xl hover:shadow-lg">
                <div class="flex items-center mb-3">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $review->rating)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-500" style="color: #F59E0B;" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                            </svg>
                        @endif
                    @endfor
                    <span class="ml-2 text-sm font-medium text-gray-600 font-inter">{{ $review->rating }}/5</span>
                </div>
                
                <!-- Review Image (if exists) -->
                @if($review->image_path)
                    <div class="mb-4">
                        <img src="{{ Storage::url($review->image_path) }}" alt="Review image" class="shadow-sm rounded-xl max-h-48">
                    </div>
                @endif
                
                <!-- Review Comment -->
                <p class="mb-4 leading-relaxed text-gray-700 whitespace-pre-line font-inter">{{ $review->comment }}</p>
                
                <!-- Review Metadata & Reply Button -->
                <div class="flex items-center justify-between">
                    <div class="text-xs font-medium text-gray-500 font-inter">
                        Posted by <span class="font-semibold text-gray-700">{{ $review->user->name }}</span> on {{ $review->created_at->format('j/n/Y') }}
                    </div>
                    <button type="button" class="flex items-center text-sm font-semibold text-orange-500 transition-colors duration-200 reply-button hover:text-orange-700 font-inter" data-review-id="{{ $review->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Reply
                    </button>
                </div>
                
                <!-- Comment Form (Hidden by default) -->
                <div id="comment-form-{{ $review->id }}" class="hidden pt-4 mt-4 border-t border-gray-200 comment-form">
                    <form method="POST" action="{{ route('comments.store') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="review_id" value="{{ $review->id }}">
                        
                        <!-- Comment Textarea -->
                        <div>
                            <label for="comment-{{ $review->id }}" class="sr-only">Write a comment</label>
                            <textarea 
                                id="comment-{{ $review->id }}"
                                name="content" 
                                rows="3" 
                                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-inter"
                                placeholder="Write a thoughtful comment..."
                                required
                                maxlength="500"
                            ></textarea>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-400 font-inter">Maximum 500 characters</span>
                                <span class="text-xs text-gray-400 font-inter char-counter">0/500</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <!-- Emoji/Reaction buttons (optional) -->
                                <div class="items-center hidden space-x-1 sm:flex">
                                    <span class="text-xs text-gray-500 font-inter">Quick reactions:</span>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 reaction-btn" data-reaction="💯">💯</button>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 reaction-btn" data-reaction="🤩">🤩</button>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 reaction-btn" data-reaction="❤️">❤️</button>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 reaction-btn" data-reaction="🔥">🔥</button>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <button type="button" class="px-3 py-2 text-sm font-semibold text-gray-600 transition-colors duration-200 rounded-md cancel-reply-button hover:text-gray-800 font-inter hover:bg-gray-100">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white transition-all duration-200 bg-orange-500 rounded-lg shadow-sm hover:bg-orange-600 hover:shadow-md font-inter disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Post Comment
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
              
                {{-- Comments Section --}}
                @include('reviews.partials.comments', ['review' => $review])
            </div>
            
           
        @empty
            <p class="py-8 text-center text-gray-500 font-inter">No reviews yet. Be the first to review this recipe!</p>
        @endforelse

        <!-- Pagination -->
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle review form
            const addReviewButton = document.getElementById('add-review-button');
            const cancelReviewButton = document.getElementById('cancel-review-button');
            const reviewFormContainer = document.getElementById('review-form-container');
            
            if (addReviewButton && cancelReviewButton && reviewFormContainer) {
                addReviewButton.addEventListener('click', function() {
                    reviewFormContainer.classList.remove('hidden');
                    addReviewButton.classList.add('hidden');
                });
                
                cancelReviewButton.addEventListener('click', function() {
                    reviewFormContainer.classList.add('hidden');
                    addReviewButton.classList.remove('hidden');
                    
                    // Reset the form
                    const form = reviewFormContainer.querySelector('form');
                    if (form) {
                        form.reset();
                        // Reset star rating to 0
                        updateStars(0);
                        document.getElementById('rating-value').value = 0;
                    }
                });
            }
            
            // Star rating functionality
            const starButtons = document.querySelectorAll('.star-btn');
            const ratingInput = document.getElementById('rating-value');

            function updateStars(rating) {
                starButtons.forEach(button => {
                    const value = parseInt(button.getAttribute('data-rating'));
                    if (value <= rating) {
                        button.classList.remove('text-gray-300');
                        button.classList.add('text-yellow-500');
                    } else {
                        button.classList.remove('text-yellow-500');
                        button.classList.add('text-gray-300');
                    }
                    
                    // Also update the SVG fill for better visibility
                    const svg = button.querySelector('svg');
                    if (svg) {
                        if (value <= rating) {
                            svg.style.color = '#F59E0B'; // yellow-500 color
                        } else {
                            svg.style.color = '#D1D5DB'; // gray-300 color
                        }
                    }
                });
            }

            if (starButtons.length > 0 && ratingInput) {
                starButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        ratingInput.value = rating;
                        updateStars(rating);
                    });
                });

                // Initialize stars if old value exists
                updateStars(parseInt(ratingInput.value) || 0);
            }
            
            // Comment/Reply functionality
            const replyButtons = document.querySelectorAll('.reply-button');
            const cancelReplyButtons = document.querySelectorAll('.cancel-reply-button');
            
            replyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const reviewId = this.getAttribute('data-review-id');
                    const commentForm = document.getElementById(`comment-form-${reviewId}`);
                    
                    if (commentForm) {
                        // Hide any other open comment forms first
                        document.querySelectorAll('.comment-form').forEach(form => {
                            if (form.id !== `comment-form-${reviewId}` && !form.classList.contains('hidden')) {
                                form.classList.add('hidden');
                            }
                        });
                        
                        // Toggle the clicked comment form
                        commentForm.classList.toggle('hidden');
                        
                        // Focus the textarea if form is visible
                        if (!commentForm.classList.contains('hidden')) {
                            const textarea = commentForm.querySelector('textarea');
                            if (textarea) {
                                textarea.focus();
                            }
                        }
                    }
                });
            });
            
            cancelReplyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const commentForm = this.closest('.comment-form');
                    if (commentForm) {
                        commentForm.classList.add('hidden');
                        const textarea = commentForm.querySelector('textarea');
                        if (textarea) {
                            textarea.value = '';
                        }
                    }
                });
            });

            // Character counter for comment textarea
            const commentTextareas = document.querySelectorAll('textarea[name="content"]');
            
            commentTextareas.forEach(textarea => {
                const charCounter = textarea.closest('form').querySelector('.char-counter');
                
                if (charCounter) {
                    textarea.addEventListener('input', function() {
                        const length = this.value.length;
                        charCounter.textContent = `${length}/500`;
                        
                        // Change color based on character count
                        if (length > 450) {
                            charCounter.classList.add('text-red-500');
                            charCounter.classList.remove('text-gray-400', 'text-yellow-500');
                        } else if (length > 350) {
                            charCounter.classList.add('text-yellow-500');
                            charCounter.classList.remove('text-gray-400', 'text-red-500');
                        } else {
                            charCounter.classList.add('text-gray-400');
                            charCounter.classList.remove('text-yellow-500', 'text-red-500');
                        }
                        
                        // Disable submit button if over limit
                        const submitBtn = this.closest('form').querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = length > 500;
                        }
                    });
                }
            });
            
            // Quick reaction buttons
            const reactionButtons = document.querySelectorAll('.reaction-btn');
            reactionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const reaction = this.getAttribute('data-reaction');
                    const textarea = this.closest('form').querySelector('textarea');
                    if (textarea) {
                        textarea.value += reaction + ' ';
                        textarea.focus();
                        // Trigger input event to update character counter
                        textarea.dispatchEvent(new Event('input'));
                    }
                });
            });
        });
    </script>
</section>