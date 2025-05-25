<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto">
        {{-- Back button --}}
        <div class="flex items-center justify-start mb-6 space-x-2">
            <button type="button" onclick="window.history.back()"
                class="text-gray-600 hover:text-orange-500 focus:outline-none">
                <x-heroicon-o-arrow-long-left class="h-10 w-10" />
            </button>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Review Details</h2>
        </div>

        {{-- Review content --}}
        <div class="bg-white shadow rounded-lg p-8 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="font-bold text-lg">{{ $review->user->name }}</span>
                        <span class="text-gray-500">reviewed</span>
                        <a href="{{ route('recipes.show', $review->recipe) }}" 
                           class="text-orange-600 hover:text-orange-800 font-medium">
                            {{ $review->recipe->title }}
                        </a>
                    </div>
                    
                    {{-- Rating --}}
                    <div class="flex items-center space-x-2 mb-3">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-600">({{ $review->rating }}/5)</span>
                        <span class="text-gray-500">•</span>
                        <span class="text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Edit/Delete buttons for review owner --}}
                @if(Auth::check() && Auth::id() === $review->user_id)
                    <div class="flex space-x-2">
                        <a href="{{ route('reviews.edit', $review) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Edit Review
                        </a>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this review?')"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete Review
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Review image --}}
            @if($review->image_path)
                <div class="mb-4">
                    <img src="{{ Storage::url($review->image_path) }}" 
                         alt="Review image" 
                         class="max-w-md rounded-lg">
                </div>
            @endif

            {{-- Review comment --}}
            <div class="prose max-w-none">
                <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
            </div>
        </div>

        {{-- Comments Section --}}
        @include('partials.comments', ['review' => $review])
    </div>
</x-app-layout>