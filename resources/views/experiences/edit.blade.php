@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <div class="py-6 max-w-2xl mx-auto">
        <div class="flex items-center justify-start mb-6 space-x-2">
            <button type="button" onclick="window.history.back()" class="text-gray-600 hover:text-orange-500 focus:outline-none">
                <x-heroicon-o-arrow-long-left class="h-10 w-10" />
            </button>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit your culinary experience</h2>
        </div>
        <div class="bg-white shadow rounded-lg p-8">
            <form method="POST" action="{{ route('experiences.update', $experience->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="title">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $experience->title) }}" required class="w-full border border-gray-300 rounded px-4 py-2" />
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required class="w-full border border-gray-300 rounded px-4 py-2">{{ old('description', $experience->description) }}</textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="category">Category</label>
                    <select name="category" id="category" required class="w-full border border-gray-300 rounded px-4 py-2">
                        <option value="">Select category</option>
                        <option value="Cooking Class" @if(old('category', $experience->category)=='Cooking Class') selected @endif>Cooking Class</option>
                        <option value="Food Tour" @if(old('category', $experience->category)=='Food Tour') selected @endif>Food Tour</option>
                        <option value="Restaurant" @if(old('category', $experience->category)=='Restaurant') selected @endif>Restaurant</option>
                    </select>
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="location">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $experience->location) }}" required class="w-full border border-gray-300 rounded px-4 py-2" />
                    @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="date">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $experience->date) }}" required class="w-full border border-gray-300 rounded px-4 py-2" />
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="image">Upload New Cover Image</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full border border-gray-300 rounded px-4 py-2" />
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @if($experience->image_url)
                        <div class="mt-2">
                            <span class="block text-gray-600 text-sm mb-1">Current Image:</span>
                            
                            @if(Str::startsWith($experience->image_url, '/storage/'))
                                <img src="{{ asset($experience->image_url) }}" alt="Current Cover" class="h-32 rounded">
                            @else
                                <img src="{{ $experience->image_url }}" alt="Current Cover" class="h-32 rounded">
                            @endif
                        </div>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <a href="{{ route('experiences.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded">
                        Update Experience
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 