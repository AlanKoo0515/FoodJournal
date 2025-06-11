@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto">
        <div class="flex justify-end mb-6">
            <a href="{{ route('experiences.create') }}"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded flex items-center">
                <span class="text-xl mr-2">+</span> Add Experience
            </a>
        </div>
        <form method="GET" action="{{ route('experiences.index') }}" class="mb-6 flex flex-col md:flex-row gap-2 w-full"
            id="searchForm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search experiences..."
                class="w-full md:w-2/4 border border-gray-300 rounded px-4 py-2" id="searchInput" />
            <select name="category" onchange="this.form.submit()"
                class="w-full md:w-1/4 border border-gray-300 rounded px-4 py-2">
                <option value="">All Categories</option>
                <option value="Cooking Class" @if (request('category') == 'Cooking Class') selected @endif>Cooking Class</option>
                <option value="Food Tour" @if (request('category') == 'Food Tour') selected @endif>Food Tour</option>
                <option value="Restaurant" @if (request('category') == 'Restaurant') selected @endif>Restaurant</option>
            </select>
            <select name="ownership" onchange="this.form.submit()"
                class="w-full md:w-1/4 border border-gray-300 rounded px-4 py-2">
                <option value="">All Posts</option>
                <option value="my" @if (request('ownership') == 'my') selected @endif>My Posts</option>
                <option value="others" @if (request('ownership') == 'others') selected @endif>Others' Posts</option>
            </select>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const searchForm = document.getElementById('searchForm');
                let timeout = null;
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        searchForm.submit();
                    }, 400); // Debounce for 400ms
                });
            });
        </script>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($experiences as $experience)
                <div class="bg-white rounded-lg shadow p-0 flex flex-col">
                    <div class="relative">
                        @if ($experience->image_url)
                            @if (Str::startsWith($experience->image_url, '/storage/app/public/experiences'))
                                <img src="{{ $experience->image_url }}" alt="{{ $experience->title }}"
                                    class="w-full h-64 object-cover rounded-t-lg">
                            @else
                                <img src="{{ $experience->image_url }}" alt="{{ $experience->title }}"
                                    class="w-full h-64 object-cover rounded-t-lg">
                            @endif
                        @else
                            <div
                                class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-t-lg text-gray-400">
                                <span>No Image</span>
                            </div>
                        @endif
                        @if (auth()->check() && auth()->id() === $experience->user_id)
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <a href="{{ route('experiences.edit', $experience->id) }}"
                                    class="bg-white p-2 rounded-md shadow hover:bg-gray-100" title="Edit">
                                    <x-heroicon-o-pencil-square class="h-5 w-5 text-orange-500" />
                                </a>
                                <form action="{{ route('experiences.destroy', $experience->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white p-2 rounded-md shadow hover:bg-gray-100"
                                        title="Delete">
                                        <x-heroicon-o-trash class="h-5 w-5 text-red-500" />
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold mb-2">{{ $experience->title }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $experience->description }}</p>
                        <span
                            class="mb-2 mr-60 px-3 py-1 rounded-md text-sm font-bold text-center
                            @if ($experience->category === 'Cooking Class') bg-green-200 text-green-800
                            @elseif($experience->category === 'Food Tour') bg-yellow-200 text-yellow-800
                            @elseif($experience->category === 'Restaurant') bg-red-200 text-red-800
                            @else bg-gray-300 text-gray-800 @endif">
                            {{ $experience->category }}
                        </span>
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <x-heroicon-o-map-pin class="h-5 w-5 mr-2 align-middle" />
                            {{ $experience->location }}
                        </div>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <x-heroicon-o-calendar class="h-5 w-5 mr-2 align-middle" />
                            {{ \Carbon\Carbon::parse($experience->date)->format('j/n/Y') }}
                        </div>
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-xs text-gray-400">Posted at
                                {{ $experience->created_at->format('j/n/Y') }}</span>
                            <a href="{{ route('experiences.show', $experience->id) }}"
                                class="text-orange-500 hover:underline font-semibold text-sm flex items-center">
                                View Experience
                                <x-heroicon-o-arrow-long-right class="h-4 w-4 ml-1" />
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-12">
                    No culinary experiences found.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $experiences->links() }}
        </div>
    </div>
</x-app-layout>
