{{-- resources/views/partials/comments.blade.php --}}
<!-- Display Comments with Edit Functionality -->
@if($review->comments && $review->comments->count() > 0)
    <div class="pt-3 mt-3 border-t border-gray-200">
        <h4 class="mb-2 text-sm font-medium text-gray-700">Comments ({{ $review->comments->count() }})</h4>
        
        @foreach($review->comments as $comment)
            <div class="pl-4 mb-3 border-l-2 border-gray-200" id="comment-{{ $comment->id }}">
                <!-- Comment Display Mode -->
                <div class="comment-display" id="display-{{ $comment->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm text-gray-700">{{ $comment->content }}</p>
                            <div class="mt-1 text-xs text-gray-400">
                                {{ $comment->user->name }} • {{ $comment->created_at->diffForHumans() }}
                                @if($comment->created_at != $comment->updated_at)
                                    <span class="text-gray-500">(edited)</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Edit/Delete buttons (only for comment owner) -->
                        @if(Auth::check() && Auth::id() === $comment->user_id)
                            <div class="flex space-x-2 ml-2">
                                <button type="button" 
                                    onclick="editComment({{ $comment->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800">
                                    Edit
                                </button>
                                <button type="button" 
                                    onclick="deleteComment({{ $comment->id }})"
                                    class="text-xs text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Comment Edit Mode (Hidden by default) -->
                <div class="comment-edit hidden" id="edit-{{ $comment->id }}">
                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea 
                            name="content" 
                            rows="2" 
                            class="w-full p-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500 mb-2"
                            required>{{ $comment->content }}</textarea>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" 
                                onclick="cancelEditComment({{ $comment->id }})"
                                class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-2 py-1 text-xs text-white bg-orange-500 rounded hover:bg-orange-600">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteCommentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-medium mb-4">Delete Comment</h3>
            <p class="text-gray-600 mb-6">Are you sure you want to delete this comment? This action cannot be undone.</p>
            <div class="flex space-x-3 justify-end">
                <button onclick="closeDeleteCommentModal()" 
                    class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <form id="deleteCommentForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Add this to your existing script section
function editComment(commentId) {
    // Hide display mode and show edit mode
    document.getElementById('display-' + commentId).classList.add('hidden');
    document.getElementById('edit-' + commentId).classList.remove('hidden');
    
    // Focus on the textarea
    const textarea = document.querySelector('#edit-' + commentId + ' textarea');
    if (textarea) {
        textarea.focus();
        // Move cursor to end
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    }
}

function cancelEditComment(commentId) {
    // Show display mode and hide edit mode
    document.getElementById('display-' + commentId).classList.remove('hidden');
    document.getElementById('edit-' + commentId).classList.add('hidden');
    
    // Reset textarea to original value
    const textarea = document.querySelector('#edit-' + commentId + ' textarea');
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