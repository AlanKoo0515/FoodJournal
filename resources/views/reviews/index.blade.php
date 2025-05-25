@php
    use Illuminate\Support\Str;

    $currentRating = request('rating');
    $currentHasImage = request('has_image');
    $ownership = request('ownership');
@endphp

<x-app-layout>
    <div class="py-6 mx-auto max-w-7xl">
        <div class="py-6 mx-auto max-w-7xl">
            <div class="flex items-center justify-between mb-6">
                <span class="block"></span>
                <!-- Quick Guide Button -->
                <div class="relative">
                    <button id="quick-guide-btn"
                        class="flex items-center px-3 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg"
                        data-toggle="tooltip" title="Click for quick filtering guide">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Quick Guide
                    </button>

                    <!-- Quick Guide Popup -->
                    <div id="quick-guide-popup"
                        class="absolute right-0 top-12 bg-white border rounded-lg shadow-lg p-4 z-50 hidden w-[24rem] break-words">
                        <div class="space-y-2 text-sm">
                            <h3 class="mb-3 font-semibold text-gray-900">Filter Reviews Easily</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-start">
                                    <span>✨Click star ratings to filter by rating</span>
                                </div>
                                <div class="flex items-center justify-start">
                                    <span>✨Filter reviews with photos</span>
                                </div>
                                <div class="pt-2 text-xs text-gray-500">
                                    💡 Tip: Combine filters for more specific results
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <form method="GET" action="{{ route('reviews.index') }}"
            class="flex flex-col w-full gap-2 mb-6 md:flex-row" id="reviewFilterForm">
            {{-- With Images button --}}
            <button type="submit" name="has_image" value="1"
                class="flex items-center gap-2 px-4 py-2 rounded-lg border transition
                    {{ $currentHasImage ? 'bg-orange-500 text-white border-orange-500' : 'bg-white border-gray-300 text-gray-700 hover:bg-orange-50' }}">
                <x-heroicon-o-camera class="w-5 h-5" />
                <span>With Images</span>
                <span class="text-xs font-semibold">({{ $counts['with_images'] ?? 0 }})</span>
            </button>
            
            {{-- Ownership Filter --}}
             @php
                $show = request()->routeIs('reviews.show') && optional($review)->user_id === Auth::id();
                $ownership = request('ownership') ?? ($show ? 'mine' : 'others');
            @endphp
            <select name="ownership" onchange="this.form.submit()"
                class="w-full px-4 py-2 border border-gray-300 rounded md:w-1/4">
                <option value="">All Reviews</option>
                <option value="mine" {{ $ownership === 'mine' ? 'selected' : '' }}>My Reviews</option>
                <option value="others" {{ $ownership === 'others' ? 'selected' : '' }}>Others' Reviews</option>
            </select>
            
            {{-- Rating buttons --}}
            <div class="flex flex-wrap w-full gap-2 md:w-2/4">
                @for ($i = 5; $i >= 1; $i--)
                    <button type="submit" name="rating" value="{{ $i }}"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg border transition
                        {{ $currentRating == $i ? 'bg-orange-500 text-white border-orange-500' : 'bg-white border-gray-300 text-gray-700 hover:bg-orange-50' }}">
                        <span>{{ $i }}</span>
                        <x-heroicon-s-star class="w-5 h-5 {{ $currentRating == $i ? 'text-white' : 'text-yellow-400' }}" />
                        <span class="text-xs font-semibold">({{ $counts["$i"] ?? 0 }})</span>
                    </button>
                @endfor
            </div>
        </form>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($reviews as $review)
                <div class="flex flex-col p-0 transition-shadow duration-300 bg-white rounded-lg shadow hover:shadow-lg">
                    <div class="relative">
                        @if ($review->image_path)
                            <img src="{{ asset('storage/' . $review->image_path) }}" alt="Review image"
                                class="object-cover w-full h-64 rounded-t-lg">
                        @else
                            <div class="flex items-center justify-center w-full h-64 text-gray-400 bg-gray-200 rounded-t-lg">
                                <span>No Image</span>
                            </div>
                        @endif
                        @if (auth()->check() && auth()->id() === $review->user_id)
                            <div class="absolute flex space-x-2 top-2 right-2">
                                <a href="{{ route('reviews.edit', $review->id) }}"
                                    class="p-2 transition-colors bg-white rounded-md shadow hover:bg-gray-100" title="Edit">
                                    <x-heroicon-o-pencil-square class="w-5 h-5 text-orange-500" />
                                </a>
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 transition-colors bg-white rounded-md shadow hover:bg-gray-100"
                                        title="Delete">
                                        <x-heroicon-o-trash class="w-5 h-5 text-red-500" />
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col flex-1 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold">{{ $review->recipe->title }}</h3>
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500" style="color: #F59E0B;" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="mb-4 text-gray-600 line-clamp-3">{{ Str::limit($review->comment, 150) }}</p>
                        <div class="flex items-center mb-4 text-sm text-gray-500">
                            <x-heroicon-o-user class="w-5 h-5 mr-2 align-middle" />
                            {{ $review->user->name }}
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-xs text-gray-400">Posted at {{ $review->created_at->format('j/n/Y') }}</span>
                            <a href="{{ route('recipes.show', $review->recipe_id) }}#reviews"
                                class="flex items-center text-sm font-semibold text-orange-500 transition-colors hover:underline">
                                View Review
                                <x-heroicon-o-arrow-long-right class="w-4 h-4 ml-1" />
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-gray-500">
                    <x-heroicon-o-document-text class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                    <h3 class="mb-2 text-lg font-medium text-gray-900">No reviews found</h3>
                    <p class="text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- Tooltip Container -->
        <div id="tooltip" class="absolute z-50 px-3 py-2 text-xs text-gray-800 transition-opacity duration-200 bg-white border rounded-md shadow-lg opacity-0 pointer-events-none border-white-100 bg-opacity-90 backdrop-blur-sm">
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div id="success-message" class="fixed z-50 px-4 py-3 text-white transition-transform duration-300 transform translate-x-full bg-green-500 rounded-lg shadow-lg top-4 right-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div id="error-message" class="fixed z-50 px-4 py-3 text-white transition-transform duration-300 transform translate-x-full bg-red-500 rounded-lg shadow-lg top-4 right-4">
                {{ session('error') }}
            </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize tooltips
                initializeTooltips();
                
                // Initialize quick guide
                initializeQuickGuide();
                
                // Show success/error messages
                showNotifications();
            });

            function initializeTooltips() {
                const tooltip = document.getElementById('tooltip');
                const elementsWithTooltips = document.querySelectorAll('[data-toggle="tooltip"]');
                let tooltipTimeout;
                
                elementsWithTooltips.forEach(element => {
                    element.addEventListener('mouseover', function (e) {
                        const title = this.getAttribute('title');
                        if (!title) return;

                        clearTimeout(tooltipTimeout);

                        tooltip.textContent = title;
                        tooltip.classList.remove('opacity-0');

                        this.setAttribute('data-original-title', title);
                        this.removeAttribute('title');

                        const rect = this.getBoundingClientRect();
                        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                        tooltip.style.top = rect.bottom + 8 + 'px';

                        // Auto-hide tooltip after 2 seconds
                        tooltipTimeout = setTimeout(() => {
                            tooltip.classList.add('opacity-0');
                            
                            const originalTitle = this.getAttribute('data-original-title');
                            if (originalTitle) {
                                this.setAttribute('title', originalTitle);
                            }
                        }, 1000);
                    });

                    element.addEventListener('mouseleave', function () {
                        
                        clearTimeout(tooltipTimeout);
                        
                        tooltip.classList.add('opacity-0');

                        const originalTitle = this.getAttribute('data-original-title');
                        if (originalTitle) {
                            this.setAttribute('title', originalTitle);
                        }
                    });
                });
            }


            function initializeQuickGuide() {
                const guideBtn = document.getElementById('quick-guide-btn');
                const guidePopup = document.getElementById('quick-guide-popup');
                
                if (guideBtn && guidePopup) {
                    guideBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        guidePopup.classList.toggle('hidden');
                    });
                    
                    document.addEventListener('click', function(e) {
                        if (!guideBtn.contains(e.target) && !guidePopup.contains(e.target)) {
                            guidePopup.classList.add('hidden');
                        }
                    });
                }
            }

            function showNotifications() {
                const successMessage = document.getElementById('success-message');
                const errorMessage = document.getElementById('error-message');
                
                if (successMessage) {
                    setTimeout(() => {
                        successMessage.classList.remove('translate-x-full');
                    }, 100);
                    setTimeout(() => {
                        successMessage.classList.add('translate-x-full');
                    }, 4000);
                }
                
                if (errorMessage) {
                    setTimeout(() => {
                        errorMessage.classList.remove('translate-x-full');
                    }, 100);
                    setTimeout(() => {
                        errorMessage.classList.add('translate-x-full');
                    }, 4000);
                }
            }
        </script>
</x-app-layout>