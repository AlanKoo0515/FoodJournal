{{-- Display Comments Component --}}
@if($review->comments && $review->comments->count() > 0)
    <div class="pt-3 mt-3 border-t border-gray-200">
        <h4 class="mb-2 text-sm font-medium text-gray-700 font-inter">Comments ({{ $review->comments->count() }})</h4>
        
        @foreach($review->comments as $comment)
            <div class="pl-4 mb-3 border-l-2 border-gray-200" id="comment-{{ $comment->id }}">
                <!-- Comment Display Mode -->
                <div class="comment-display" id="display-{{ $comment->id }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 font-inter">{{ $comment->content }}</p>
                            <div class="mt-1 text-xs text-gray-400 font-inter">
                                {{ $comment->user->name }} • {{ $comment->created_at->diffForHumans() }}
                                @if($comment->created_at != $comment->updated_at)
                                    <span class="text-gray-500">(edited)</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Edit/Delete buttons (only for comment owner) -->
                        @if(Auth::check() && Auth::id() === $comment->user_id)
                            <div class="flex ml-2 space-x-2">
                                <button type="button" 
                                    onclick="editComment({{ $comment->id }})"
                                    class="text-xs text-blue-600 transition-colors duration-200 hover:text-blue-800 font-inter">
                                    Edit
                                </button>
                                <button type="button" 
                                    onclick="deleteComment({{ $comment->id }})"
                                    class="text-xs text-red-600 transition-colors duration-200 hover:text-red-800 font-inter">
                                    Delete
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Comment Edit Mode (Hidden by default) -->
                <div class="hidden comment-edit" id="edit-{{ $comment->id }}">
                    <form method="POST" action="{{ route('comments.update', $comment->id) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="edit-content-{{ $comment->id }}" class="sr-only">Edit comment</label>
                            <textarea 
                                id="edit-content-{{ $comment->id }}"
                                name="content" 
                                rows="3" 
                                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 font-inter"
                                placeholder="Edit your comment..."
                                required
                                maxlength="500"
                            >{{ $comment->content }}</textarea>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-gray-400 font-inter">Maximum 500 characters</span>
                                <span class="text-xs text-gray-400 font-inter edit-char-counter">{{ strlen($comment->content) }}/500</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <!-- Quick reaction buttons for editing -->
                                <div class="items-center hidden space-x-1 sm:flex">
                                    <span class="text-xs text-gray-500 font-inter">Quick reactions:</span>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 edit-reaction-btn" data-reaction="👍">👍</button>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 edit-reaction-btn" data-reaction="👏">👏</button>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 edit-reaction-btn" data-reaction="❤️">❤️</button>
                                    <button type="button" class="text-lg transition-transform duration-200 hover:scale-110 edit-reaction-btn" data-reaction="😋">😋</button>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <button type="button" 
                                    onclick="cancelEditComment({{ $comment->id }})"
                                    class="px-3 py-1 text-xs text-gray-600 transition-colors duration-200 hover:text-gray-800 font-inter">
                                    Cancel
                                </button>
                                <button type="submit" 
                                    class="px-3 py-1 text-xs text-white transition-colors duration-200 bg-orange-500 rounded hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed font-inter">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteCommentModal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-lg">
            <h3 class="mb-4 text-lg font-medium font-inter">Delete Comment</h3>
            <p class="mb-6 text-gray-600 font-inter">Are you sure you want to delete this comment? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteCommentModal()" 
                    class="px-4 py-2 text-gray-600 transition-colors duration-200 hover:text-gray-800 font-inter">
                    Cancel
                </button>
                <form id="deleteCommentForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-4 py-2 text-white transition-colors duration-200 bg-red-500 rounded hover:bg-red-600 font-inter">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Edit comment functionality
function editComment(commentId) {
    // Hide display mode and show edit mode
    document.getElementById('display-' + commentId).classList.add('hidden');
    document.getElementById('edit-' + commentId).classList.remove('hidden');
    
    // Focus on the textarea
    const textarea = document.querySelector('#edit-content-' + commentId);
    if (textarea) {
        textarea.focus();
        // Move cursor to end
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        
        // Set up character counter for edit mode
        const charCounter = document.querySelector('#edit-' + commentId + ' .edit-char-counter');
        if (charCounter) {
            const updateCounter = function() {
                const length = textarea.value.length;
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
                const submitBtn = textarea.closest('form').querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = length > 500;
                }
            };
            
            textarea.addEventListener('input', updateCounter);
            updateCounter(); // Initial call
        }
        
        // Set up edit reaction buttons
        const editReactionButtons = document.querySelectorAll('#edit-' + commentId + ' .edit-reaction-btn');
        editReactionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reaction = this.getAttribute('data-reaction');
                textarea.value += reaction + ' ';
                textarea.focus();
                // Trigger input event to update character counter
                textarea.dispatchEvent(new Event('input'));
            });
        });
    }
}

function cancelEditComment(commentId) {
    // Show display mode and hide edit mode
    document.getElementById('display-' + commentId).classList.remove('hidden');
    document.getElementById('edit-' + commentId).classList.add('hidden');
    
    // Reset textarea to original value
    const textarea = document.querySelector('#edit-content-' + commentId);
    const originalContent = document.querySelector('#display-' + commentId + ' p').textContent;
    if (textarea) {
        textarea.value = originalContent;
    }
}

function deleteComment(commentId) {
    // Set the form action for deletion
    const deleteForm = document.getElementById('deleteCommentForm');
    deleteForm.action = '/comments/' + commentId;
    
    // Show the modal
    document.getElementById('deleteCommentModal').classList.remove('hidden');
}

function closeDeleteCommentModal() {
    document.getElementById('deleteCommentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteCommentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteCommentModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteCommentModal();
    }
});
</script>