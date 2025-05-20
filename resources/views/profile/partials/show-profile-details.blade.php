<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Page') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("This is your profile information.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Profile Image -->
        <div class="flex items-center space-x-4">
            <img src="{{ $user->profile_photo_url ?? asset('images/default-profile.png') }}" alt="Profile Photo" class="w-20 h-20 rounded-full object-cover border" />
            <div>
                <p class="text-sm text-gray-600">{{ __('Your current profile picture') }}</p>
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <div class="mt-1 block w-full border rounded px-4 py-2 bg-gray-100 text-gray-900">
                {{ $user->name }}
            </div>
        </div>

        <!-- Contact Number -->
        <div>
            <x-input-label for="contact_number" :value="__('Contact Number')" />
            <div class="mt-1 block w-full border rounded px-4 py-2 bg-gray-100 text-gray-900">
                {{ $user->contact_number ?? __('Not provided') }}
            </div>
        </div>

        <!-- About -->
        <div>
            <x-input-label for="bio" :value="__('About')" />
            <div class="mt-1 block w-full border rounded px-4 py-2 bg-gray-100 text-gray-900">
                {{ $user->bio ?? __('No description provided.') }}
            </div>
        </div>
    </div>
</section>
