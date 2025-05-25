{{-- resources/views/reviews/partials/comment-form.blade.php --}}

<form method="POST" action="{{ route('comments.store') }}" class="space-y-3">
    @csrf
    <input type="hidden" name="review_id" value="{{ $review->id }}">
    
    {{-- Comment Textarea --}}
    <div>
        <label for="comment-{{ $review->id }}" class="sr-only">Write a comment</label>
        <textarea 
            id="comment-{{ $review->id }}"
            name="content" 
            rows="3" 
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm font-inter resize-none"
            placeholder="Write a thoughtful comment..."
            required
            maxlength="500"
        ></textarea>
        <div class="flex justify-between items-center mt-1">
            <span class="text-xs text-gray-400 font-inter">Maximum 500 characters</span>
            <span class="text-xs text-gray-400 font-inter char-counter">0/500</span>
        </div>
    </div>
    
    {{-- Action Buttons --}}
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-2">
            {{-- Emoji/Reaction buttons (optional) --}}
            <div class="hidden sm:flex items-center space-x-1">
                <span class="text-xs text-gray-500 font-inter">Quick reactions:</span>
                <button type="button" class="text-lg hover:scale-110 transition-transform duration-200 reaction-btn" data-reaction="👍">👍</button>
                <button type="button" class="text-lg hover:scale-110 transition-transform duration-200 reaction-btn" data-reaction="👏">👏</button>
                <button type="button" class="text-lg hover:scale-110 transition-transform duration-200 reaction-btn" data-reaction="❤️">❤️</button>
                <button type="button" class="text-lg hover:scale-110 transition-transform duration-200 reaction-btn" data-reaction="😋">😋</button>
            </div>
        </div>
        
        <div class="flex justify-end space-x-2">
            <button type="button" class="px-3 py-2 text-sm text-gray-600 cancel-reply-button hover:text-gray-800 font-semibold font-inter transition-colors duration-200 rounded-md hover:bg-gray-100">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 text-sm text-white bg-orange-500 rounded-lg shadow-sm hover:bg-orange-600 hover:shadow-md transition-all duration-200 font-semibold font-inter disabled:opacity-50 disabled:cursor-not-allowed">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for comment textarea
    const textarea = document.getElementById('comment-{{ $review->id }}');
    const charCounter = textarea?.closest('form').querySelector('.char-counter');
    
    if (textarea && charCounter) {
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length}/500`;
            
            // Change color based on character count
            if (length > 450) {
                charCounter.classList.add('text-red-500');
                charCounter.classList.remove('text-gray-400');
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