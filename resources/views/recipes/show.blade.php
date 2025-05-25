@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <button type="button" onclick="window.history.back()" class="flex items-center text-gray-600 hover:text-orange-500 font-semibold">
                <x-heroicon-o-arrow-left class="h-5 w-5 mr-2" />
                Back
            </button>
        </div>

        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <!-- Left side: Title + Info -->
            <div class="flex-1 min-w-0">
                <!-- Title -->
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2 leading-normal">
                    {{ $recipe->title }}    
                </h1>

                <!-- Recipe Info -->
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-gray-500 text-sm md:text-base">
                    <span class="flex items-center">
                        <x-heroicon-o-clock class="h-6 w-6 mr-1 text-gray-400" />
                        {{ $recipe->cook_time }} minutes
                    </span>
                    <span class="flex items-center">
                        <x-heroicon-o-user-group class="h-6 w-6 mr-1 text-gray-400" />
                        {{ $recipe->servings }} servings
                    </span>
                    <span class="flex items-center">
                        <x-heroicon-o-fire class="h-6 w-6 mr-1 text-gray-400" />
                        {{ $recipe->calories_per_serving }} calories/serving
                    </span>
                </div>
            </div>

            <!-- Right side: Buttons -->
            @if(auth()->check() && auth()->id() === $recipe->user_id)
            <div class="flex space-x-2 ml-4 flex-shrink-0">
                <a href="{{ route('recipes.edit', $recipe->id) }}"
                class="bg-white border border-gray-200 p-2 rounded-md hover:bg-gray-100 transition"
                title="Edit">
                    <x-heroicon-o-pencil-square class="h-5 w-5 text-gray-700" />
                </a>

                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-white border border-gray-200 p-2 rounded-md hover:bg-gray-100 transition"
                            title="Delete">
                        <x-heroicon-o-trash class="h-5 w-5 text-red-500" />
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Image and Content Section -->
        <div class="rounded-xl overflow-hidden bg-gray-100 mb-8 border border-gray-200">
            @if($recipe->image_url)
                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-full h-[32rem] object-cover">
            @else
                <div class="w-full h-[32rem] flex items-center justify-center bg-gray-100">
                    <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <rect x="8" y="8" width="32" height="32" rx="4" fill="#f3f4f6" />
                        <path d="M16 32l6-8 6 8 6-12" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="20" cy="20" r="2" fill="#d1d5db" />
                    </svg>
                </div>
            @endif
            <div class="bg-white p-8">
                <!-- Ingredients Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Ingredients</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($recipe->ingredients)) !!}
                    </div>
                </div>

                <!-- Instructions Section -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Instructions</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($recipe->instructions)) !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- Reviews Section -->
        <!-- Include the review section -->
        @include('reviews.create')
        </section>
    </div>
</x-app-layout>
