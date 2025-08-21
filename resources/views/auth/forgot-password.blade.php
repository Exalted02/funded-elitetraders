<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center mt-4">
			<button type="submit" class="items-center px-4 py-2 bg-color-800 border border-transparent1 rounded-md font-semibold tracking-widest hover:bg-color-700 focus:bg-color-700 active:bg-color-900 focus:outline-none focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 common-button">
				Email Password Reset Link
			</button>
        </div>
    </form>
</x-guest-layout>
