<x-app-layout>
    <div class="py-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-start mb-6 space-x-2">
            <button type="button" onclick="window.history.back()"
                class="text-gray-600 hover:text-orange-500 focus:outline-none">
                <x-heroicon-o-arrow-long-left class="h-10 w-10" />
            </button>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Share New Recipe</h2>
        </div>

        <div class="bg-white shadow rounded-lg p-8">
            <form method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="recipe_name">Recipe Name</label>
                            <input type="text" name="recipe_name" id="recipe_name" value="{{ old('recipe_name') }}"
                                required class="w-full border border-gray-300 rounded px-4 py-2" />
                            @error('recipe_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="serving_size">Serving Size</label>
                            <input type="text" name="serving_size" id="serving_size" value="{{ old('serving_size') }}"
                                required class="w-full border border-gray-300 rounded px-4 py-2" />
                            @error('serving_size')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="instruction">Instruction</label>
                            <textarea name="instruction" id="instruction" rows="8" required
                                class="w-full border border-gray-300 rounded px-4 py-2">{{ old('instruction') }}</textarea>
                            @error('instruction')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="calories">Calories Per Serving</label>
                                <input type="number" name="calories" id="calories" value="{{ old('calories') }}"
                                    required class="w-full border border-gray-300 rounded px-4 py-2" />
                                @error('calories')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="cook_time">Cook Time</label>
                                <input type="text" name="cook_time" id="cook_time" value="{{ old('cook_time') }}"
                                    required class="w-full border border-gray-300 rounded px-4 py-2" />
                                @error('cook_time')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="ingredients">Ingredients</label>
                            <textarea name="ingredients" id="ingredients" rows="8" required
                                class="w-full border border-gray-300 rounded px-4 py-2">{{ old('ingredients') }}</textarea>
                            @error('ingredients')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2" for="notes">Notes</label>
                            <input type="text" name="notes" id="notes" value="{{ old('notes') }}"
                                class="w-full border border-gray-300 rounded px-4 py-2" />
                            @error('notes')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Recipe Photo</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                <input type="file" name="image" id="image" accept="image/*" class="hidden" />
                                <div class="text-center">
                                    <img id="preview" src="" alt="" class="mx-auto mb-4 hidden max-h-48">
                                    <button type="button" onclick="document.getElementById('image').click()"
                                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg mr-2">
                                        Choose photo
                                    </button>
                                    <button type="button" onclick="discardImage()"
                                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
                                        Discard changes
                                    </button>
                                </div>
                                @error('image')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center space-x-4 mt-6">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded">
                        Post recipe
                    </button>
                    <button type="button" onclick="window.history.back()"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded">
                        Discard
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('image').addEventListener('change', function() {
            readURL(this);
        });

        function discardImage() {
            document.getElementById('image').value = '';
            document.getElementById('preview').classList.add('hidden');
            document.getElementById('preview').src = '';
        }
    </script>
</x-app-layout>