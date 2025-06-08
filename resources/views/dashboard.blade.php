<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-white pt-12 pb-32 px-4">
        <!-- Welcome Section -->
        <div class="text-center mt-8 mb-16">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-4">Welcome to Food Journal</h1>
            <p class="text-lg md:text-xl text-gray-500 mb-8 max-w-2xl mx-auto">
                Share your culinary journey, discover new recipes, and connect with food enthusiasts around the world.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('recipes.index') }}"
                    class="px-8 py-3 bg-orange-500 text-white font-semibold rounded-md shadow hover:bg-orange-600 transition text-lg">Explore
                    Recipes</a>
                <a href="{{ route('profile.edit') }}"
                    class="px-8 py-3 border border-gray-300 text-gray-800 font-semibold rounded-md shadow hover:bg-gray-100 transition text-lg bg-white">My
                    Profile</a>
            </div>
        </div>

        <!-- Features Section -->
        <div class="w-full max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-10">Explore Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Manage Profile -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow p-8 flex flex-col items-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-orange-500 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Manage Profile</h3>
                    <p class="text-gray-600 mb-6">Customize your profile and share your culinary background</p>
                    <a href="{{ route('profile.edit') }}"
                        class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded-md shadow hover:bg-orange-600 transition">View
                        Profile</a>
                </div>
                <!-- Manage Recipes -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow p-8 flex flex-col items-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-orange-500 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Manage Recipes</h3>
                    <p class="text-gray-600 mb-6">Create, edit and share your favorite recipes</p>
                    <a href="{{ route('recipes.index') }}"
                        class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded-md shadow hover:bg-orange-600 transition">View
                        Recipes</a>
                </div>
                <!-- Culinary Experiences -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow p-8 flex flex-col items-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-orange-500 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 1.343-3 3 0 1.657 1.343 3 3 3s3-1.343 3-3c0-1.657-1.343-3-3-3zm0 10c-4.418 0-8-1.79-8-4V6a2 2 0 012-2h12a2 2 0 012 2v8c0 2.21-3.582 4-8 4z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Culinary Experiences</h3>
                    <p class="text-gray-600 mb-6">Document your food adventures and discoveries</p>
                    <a href="{{ route('experiences.index') }}"
                        class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded-md shadow hover:bg-orange-600 transition">View
                        Experiences</a>
                </div>
                <!-- Manage Ratings -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow p-8 flex flex-col items-center text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-orange-500 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.036 6.29a1 1 0 00.95.69h6.6c.969 0 1.371 1.24.588 1.81l-5.347 3.89a1 1 0 00-.364 1.118l2.036 6.29c.3.921-.755 1.688-1.54 1.118l-5.347-3.89a1 1 0 00-1.176 0l-5.347 3.89c-.784.57-1.838-.197-1.54-1.118l2.036-6.29a1 1 0 00-.364-1.118l-5.347-3.89c-.783-.57-.38-1.81.588-1.81h6.6a1 1 0 00.95-.69l2.036-6.29z" />
                    </svg>
                    <h3 class="text-xl font-bold mb-2">Manage Ratings</h3>
                    <p class="text-gray-600 mb-6">Rate and review recipes from the community</p>
                    <a href="{{ route('reviews.index') }}"
                        class="w-full px-4 py-2 bg-orange-500 text-white font-semibold rounded-md shadow hover:bg-orange-600 transition">View
                        Ratings</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
