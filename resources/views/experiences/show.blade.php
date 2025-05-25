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
            <!-- Left side: Title + Info aligned left -->
            <div class="flex-1 min-w-0">
                <!-- Title -->
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2 leading-normal">
                    {{ $experience->title }}    
                </h1>


                <!-- Badge, Location, Date -->
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-gray-500 text-xs md:text-sm">
                    <!-- Category Badge -->
                    <span class="px-3 py-1 rounded-md text-sm font-bold text-center text-gray-800
                        @if($experience->category === 'Cooking Class') bg-green-200 text-green-800
                        @elseif($experience->category === 'Food Tour') bg-yellow-200 text-yellow-800
                        @elseif($experience->category === 'Restaurant') bg-red-200 text-red-800
                        @else bg-gray-300 @endif">
                        {{ $experience->category ?? 'Special Dining Event' }}
                    </span>

                    <!-- Location -->
                    <span class="flex items-center gap-1 whitespace-nowrap text-sm md:text-base">
                        <x-heroicon-o-map-pin class="h-6 w-6 ml-2 text-gray-400" />
                        {{ $experience->location }}
                    </span>

                    <!-- Date -->
                    <span class="flex items-center gap-1 whitespace-nowrap text-sm md:text-base">
                        <x-heroicon-o-calendar class="h-6 w-6 ml-2 text-gray-400" />
                        {{ \Carbon\Carbon::parse($experience->date)->format('j/n/Y') }}
                    </span>
                </div>
            </div>

            <!-- Right side: Buttons -->
            @if(auth()->check() && auth()->id() === $experience->user_id)
            <div class="flex space-x-2 ml-4 flex-shrink-0">
                <a href="{{ route('experiences.edit', $experience->id) }}"
                class="bg-white border border-gray-200 p-2 rounded-md hover:bg-gray-100 transition"
                title="Edit">
                    <x-heroicon-o-pencil-square class="h-5 w-5 text-gray-700" />
                </a>

                <form action="{{ route('experiences.destroy', $experience->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this experience?');">
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


        <!-- Combined Image and Content Section -->
        <div class="rounded-xl overflow-hidden bg-gray-100 mb-8 border border-gray-200">
            @if($experience->image_url)
                <img src="{{ $experience->image_url }}" alt="{{ $experience->title }}" class="w-full h-[32rem] object-cover">
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
                <h2 class="text-lg font-bold text-gray-900 mb-2">Overview</h2>
                <p class="text-gray-700">{{ $experience->description }}</p>
            </div>
        </div>
    </div>
</x-app-layout> 