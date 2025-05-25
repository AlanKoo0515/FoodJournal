<x-app-layout>
    <div class="py-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-start mb-6 space-x-2">
            <button type="button" onclick="window.history.back()"
                class="text-gray-600 hover:text-orange-500 focus:outline-none">
                <x-heroicon-o-arrow-long-left class="h-10 w-10" />
            </button>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Post your culinary experience</h2>
        </div>
        <div class="bg-white shadow rounded-lg p-8">
            <form method="POST" action="{{ route('experiences.store') }}" enctype="multipart/form-data"
                id="experienceForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="title">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="Give your experience a title" required
                        class="required-field w-full border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded px-4 py-2" />
                    @error('title')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required placeholder="Describe your experience"
                        class="required-field w-full border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded px-4 py-2">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="category">Category</label>
                    <select name="category" id="category" required
                        class="required-field w-full border {{ $errors->has('category') ? 'border-red-500' : 'border-gray-300' }} rounded px-4 py-2">
                        <option value="">Select category</option>
                        <option value="Cooking Class" @if (old('category') == 'Cooking Class') selected @endif>Cooking Class
                        </option>
                        <option value="Food Tour" @if (old('category') == 'Food Tour') selected @endif>Food Tour</option>
                        <option value="Restaurant" @if (old('category') == 'Restaurant') selected @endif>Restaurant</option>
                    </select>
                    @error('category')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="location">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" required
                        placeholder="Where did you experience this?"
                        class="required-field w-full border {{ $errors->has('location') ? 'border-red-500' : 'border-gray-300' }} rounded px-4 py-2" />
                    @error('location')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="date">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}" required
                        class="required-field w-full border {{ $errors->has('date') ? 'border-red-500' : 'border-gray-300' }} rounded px-4 py-2" />
                    @error('date')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="image">Upload Cover Image</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full border {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300' }} rounded px-4 py-2" />
                    <p class="text-sm text-gray-500 mt-1">Allowed file types: JPEG, PNG, JPG, GIF, SVG (Max size: 10MB)
                    </p>
                    @error('image')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-between items-center">
                    <a href="{{ route('experiences.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                    <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded">
                        Add Experience
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('experienceForm').addEventListener('submit', function(event) {
            let valid = true;

            // Select all required fields (inputs, selects, textareas with .required-field class)
            const requiredFields = this.querySelectorAll('.required-field');

            requiredFields.forEach(field => {
                // Trim value and check if empty
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                event.preventDefault(); // Prevent form submission
                alert('Please fill all required fields.');
            }
        });
    </script>
</x-app-layout>
