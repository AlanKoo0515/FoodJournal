@php
    use Illuminate\Support\Str;
    $currentYear = now()->year;
    $minCreatedAt = \App\Models\Recipe::min('created_at');
    $firstYear = $minCreatedAt ? \Carbon\Carbon::parse($minCreatedAt)->format('Y') : $currentYear;
@endphp

<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto">
        <div class="flex justify-end mb-6">
            <a href="{{ route('recipes.create') }}"
                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded flex items-center">
                <span class="text-xl mr-2">+</span> Add Recipe
            </a>
        </div>

        <form method="GET" action="{{ route('recipes.index') }}" class="mb-6 flex gap-2" id="searchForm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search recipes..."
                class="w-full border border-gray-300 rounded px-4 py-2" id="searchInput" />
            <select name="servings" class="border border-gray-300 rounded px-4 py-2" onchange="this.form.submit()">
                <option value="">All Servings</option>
                <option value="1" {{ request('servings') == '1' ? 'selected' : '' }}>1</option>
                <option value="2" {{ request('servings') == '2' ? 'selected' : '' }}>2</option>
                <option value="3" {{ request('servings') == '3' ? 'selected' : '' }}>3</option>
                <option value="more" {{ request('servings') == 'more' ? 'selected' : '' }}>More</option>
            </select>
            <select name="cook_time" class="border border-gray-300 rounded px-4 py-2" onchange="this.form.submit()">
                <option value="">All Cook Times</option>
                <option value="quick" {{ request('cook_time') == 'quick' ? 'selected' : '' }}>Quick (&le; 30 min)</option>
                <option value="mid" {{ request('cook_time') == 'mid' ? 'selected' : '' }}>Mid (31-60 min)</option>
                <option value="long" {{ request('cook_time') == 'long' ? 'selected' : '' }}>Long (&gt; 60 min)</option>
            </select>
            <select name="year" class="border border-gray-300 rounded px-4 py-2" onchange="this.form.submit()">
                <option value="">All Years</option>
                @for ($year = $currentYear; $year >= $firstYear; $year--)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
        </form>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            $(function() {
                let timeout = null;
                $('#searchInput').on('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        $('#searchForm').trigger('submit');
                    }, 400);
                });

                $('#searchForm').on('change', 'select', function() {
                    $('#searchForm').trigger('submit');
                });

                $('#searchForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        type: 'GET',
                        beforeSend: function() {
                            $('#recipesGrid').html('<div class="col-span-3 text-center text-gray-400 py-12">Loading...</div>');
                        },
                        success: function(data) {
                            // Extract the recipes grid from the returned HTML
                            let html = $(data).find('#recipesGrid').html();
                            $('#recipesGrid').html(html);
                        },
                        error: function() {
                            $('#recipesGrid').html('<div class="col-span-3 text-center text-red-400 py-12">Error loading recipes.</div>');
                        }
                    });
                });
            });
        </script>

        <div id="recipesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($recipes as $recipe)
                <div class="bg-white rounded-lg shadow p-0 flex flex-col">
                    <div class="relative">
                        @if ($recipe->image_url)
                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}"
                                class="w-full h-64 object-cover rounded-t-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-t-lg text-gray-400">
                                <span>No Image</span>
                            </div>
                        @endif
                        @if (auth()->check() && auth()->id() === $recipe->user_id)
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <a href="{{ route('recipes.edit', $recipe->id) }}"
                                    class="bg-white p-2 rounded-md shadow hover:bg-gray-100" title="Edit">
                                    <x-heroicon-o-pencil-square class="h-5 w-5 text-orange-500" />
                                </a>
                                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST"
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
                        <h3 class="text-lg font-bold mb-2">{{ $recipe->title }}</h3>
                        <div class="text-gray-600 mb-4">
                            <div class="flex items-center mb-2">
                                <x-heroicon-o-clock class="h-5 w-5 mr-2" />
                                <span>{{ $recipe->cook_time }} minutes</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <x-heroicon-o-user-group class="h-5 w-5 mr-2" />
                                <span>{{ $recipe->servings }} servings</span>
                            </div>
                            <div class="flex items-center">
                                <x-heroicon-o-fire class="h-5 w-5 mr-2" />
                                <span>{{ $recipe->calories_per_serving }} calories per serving</span>
                            </div>
                        </div>
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-xs text-gray-400">Posted at {{ $recipe->created_at->format('j/n/Y') }}</span>
                            <a href="{{ route('recipes.show', $recipe->id) }}"
                                class="text-orange-500 hover:underline font-semibold text-sm flex items-center">
                                View Recipe
                                <x-heroicon-o-arrow-long-right class="h-4 w-4 ml-1" />
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500 py-12">
                    No recipes found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $recipes->links() }}
        </div>
    </div>
</x-app-layout>