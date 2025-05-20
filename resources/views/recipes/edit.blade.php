@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-start mb-6 space-x-2">
            <button type="button" onclick="window.history.back()" class="text-gray-600 hover:text-orange-500 focus:outline-none">
                <x-heroicon-o-arrow-long-left class="h-10 w-10" />
            </button>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Recipe</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-8">
            <form method="POST" action="{{ route('recipes.update', $recipe->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="title">Recipe Name</label>
                            <input type="text" name="title" id="title" 
                                value="{{ old('title', $recipe->title) }}"
                                required class="w-full border border-gray-300 rounded px-4 py-2" />
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="servings">Serving Size</label>
                            <input type="number" name="servings" id="servings" 
                                value="{{ old('servings', $recipe->servings) }}"
                                required class="w-full border border-gray-300 rounded px-4 py-2" />
                            @error('servings') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="instructions">Instructions</label>
                            <textarea name="instructions" id="instructions" rows="8" required
                                class="w-full border border-gray-300 rounded px-4 py-2">{{ old('instructions', $recipe->instructions) }}</textarea>
                            @error('instructions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="ingredients">Ingredients</label>
                            <textarea name="ingredients" id="ingredients" rows="8" required
                                class="w-full border border-gray-300 rounded px-4 py-2">{{ old('ingredients', $recipe->ingredients) }}</textarea>
                            @error('ingredients') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="calories_per_serving">Calories Per Serving</label>
                                <input type="number" name="calories_per_serving" id="calories_per_serving" 
                                    value="{{ old('calories_per_serving', $recipe->calories_per_serving) }}"
                                    required class="w-full border border-gray-300 rounded px-4 py-2" />
                                @error('calories_per_serving') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="cook_time">Cook Time (minutes)</label>
                                <input type="number" name="cook_time" id="cook_time" 
                                    value="{{ old('cook_time', $recipe->cook_time) }}"
                                    required class="w-full border border-gray-300 rounded px-4 py-2" />
                                @error('cook_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2" for="image">Upload New Recipe Image</label>
                            <input type="file" name="image" id="image" accept="image/*" 
                                class="w-full border border-gray-300 rounded px-4 py-2" />
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            @if($recipe->image_url)
                                <div class="mt-2">
                                    <span class="block text-gray-600 text-sm mb-1">Current Image:</span>
                                    <img src="{{ $recipe->image_url }}" alt="Current Recipe Image" class="h-32 rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('recipes.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded">
                        Update Recipe
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>