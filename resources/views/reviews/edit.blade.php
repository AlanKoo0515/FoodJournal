<x-app-layout>
<div class="container py-8 mx-auto">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('recipes.show', $review->recipe_id) }}#reviews" class="inline-flex items-center text-orange-500 hover:text-orange-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Recipe
            </a>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
            <h1 class="mb-6 text-2xl font-bold text-gray-900">Edit Your Review</h1>
            
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('reviews.update', $review) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Rating -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Rating</label>
                    <div class="flex items-center">
                        <div class="flex space-x-1" id="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" data-rating="{{ $i }}" class="star-btn w-10 h-10 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }} hover:text-yellow-500 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-value" value="{{ old('rating', $review->rating) }}">
                    </div>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comment -->
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700">Review Comment</label>
                    <textarea id="comment" name="comment" rows="5" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50" placeholder="Share your experience with this recipe">{{ old('comment', $review->comment) }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image Preview (if exists) -->
                @if($review->image_path)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Image</label>
                        <div class="mt-2">
                            <img src="{{ Storage::url($review->image_path) }}" alt="Current review image" class="rounded-lg max-h-48">
                        </div>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="remove_image" class="text-orange-500 border-gray-300 rounded shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Remove current image</span>
                            </label>
                        </div>
                    </div>
                @endif

                <!-- Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700">
                        {{ $review->image_path ? 'Replace Image (Optional)' : 'Image (Optional)' }}
                    </label>
                    <div class="flex items-center mt-1">
                        <input type="file" id="image" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recipe Information i don't think this necessary-->
                <div class="p-4 mb-6 rounded-lg bg-gray-50">
                    <h3 class="mb-2 text-lg font-medium text-gray-700">Recipe Information</h3>
                    <div class="flex items-center">
                        <div class="w-16 h-16 overflow-hidden bg-gray-100 rounded-md">
                            @if($review->recipe->image_path)
                                <img src="{{ Storage::url($review->recipe->image_path) }}" alt="{{ $review->recipe->title }}" class="object-cover w-full h-full">
                            @else
                                <div class="flex items-center justify-center w-full h-full text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900">{{ $review->recipe->title }}</p>
                            <p class="text-sm text-gray-600">By {{ $review->recipe->user->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('recipes.show', $review->recipe_id) }}#reviews" class="px-4 py-2 font-bold text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 font-bold text-white bg-orange-500 rounded hover:bg-orange-600">
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

            // Initialize stars with current review rating
            updateStars(parseInt(ratingInput.value) || 0);
        }
    });
</script>
</x-app-layout>
