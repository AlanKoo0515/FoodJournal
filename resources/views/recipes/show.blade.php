@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="max-w-4xl py-6 mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <button type="button" onclick="window.history.back()" class="flex items-center font-semibold text-gray-600 hover:text-orange-500">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Back
            </button>
        </div>

        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <!-- Left side: Title + Info -->
            <div class="flex-1 min-w-0">
                <!-- Title -->
                <h1 class="mb-2 text-4xl font-extrabold leading-normal text-gray-900">
                    {{ $recipe->title }}    
                </h1>

                <!-- Recipe Info -->
                <div class="flex flex-wrap items-center text-sm text-gray-500 gap-x-4 gap-y-2 md:text-base">
                    <span class="flex items-center">
                        <x-heroicon-o-clock class="w-6 h-6 mr-1 text-gray-400" />
                        {{ $recipe->cook_time }} minutes
                    </span>
                    <span class="flex items-center">
                        <x-heroicon-o-user-group class="w-6 h-6 mr-1 text-gray-400" />
                        {{ $recipe->servings }} servings
                    </span>
                    <span class="flex items-center">
                        <x-heroicon-o-fire class="w-6 h-6 mr-1 text-gray-400" />
                        {{ $recipe->calories_per_serving }} calories/serving
                    </span>
                </div>
            </div>

            <!-- Right side: Buttons -->
            @if(auth()->check() && auth()->id() === $recipe->user_id)
            <div class="flex flex-shrink-0 ml-4 space-x-2">
                <a href="{{ route('recipes.edit', $recipe->id) }}"
                class="p-2 transition bg-white border border-gray-200 rounded-md hover:bg-gray-100"
                title="Edit">
                    <x-heroicon-o-pencil-square class="w-5 h-5 text-gray-700" />
                </a>

                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 transition bg-white border border-gray-200 rounded-md hover:bg-gray-100"
                            title="Delete">
                        <x-heroicon-o-trash class="w-5 h-5 text-red-500" />
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Image and Content Section -->
        <div class="mb-8 overflow-hidden bg-gray-100 border border-gray-200 rounded-xl">
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
            <div class="p-8 bg-white">
                <!-- Ingredients Section -->
                <div class="mb-8">
                    <h2 class="mb-4 text-2xl font-bold text-gray-900">Ingredients</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($recipe->ingredients)) !!}
                    </div>
                </div>

                <!-- Instructions Section -->
                <div>
                    <h2 class="mb-4 text-2xl font-bold text-gray-900">Instructions</h2>
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
