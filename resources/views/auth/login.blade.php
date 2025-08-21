<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
			@if (session('error'))
				<ul class="text-sm text-red-600 space-y-1 mt-2">
					<li>{{ session('error') }}</li>
				</ul>
			@endif
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ show: false }">
			<x-input-label for="password" :value="__('Password')" />

			<div class="relative">
				<input
					:type="show ? 'text' : 'password'"
					id="password"
					name="password"
					required
					autocomplete="current-password"
					class="border-gray-300 focus:border-color-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
				/>

				<div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 password-view">
					<svg
						x-show="!show"
						@click="show = true"
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 text-gray-500 cursor-pointer"
						fill="none"
						viewBox="0 0 24 24"
						stroke="currentColor"
					>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
					</svg>

					<svg
						x-show="show"
						@click="show = false"
						xmlns="http://www.w3.org/2000/svg"
						class="h-5 w-5 text-gray-500 cursor-pointer"
						fill="none"
						viewBox="0 0 24 24"
						stroke="currentColor"
					>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.964 9.964 0 013.113-4.568m1.632-1.287A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.293 5.568M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							  d="M3 3l18 18"/>
					</svg>
				</div>
			</div>

			<x-input-error :messages="$errors->get('password')" class="mt-2" />
		</div>


        <!-- Remember Me -->
        <div class="flex mt-4 justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 text-white">{{ __('Remember me') }}</span>
            </label>
			@if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-white" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <div class="flex items-center mt-4">
			<button type="submit" class="items-center px-4 py-2 bg-color-800 border border-transparent1 rounded-md font-semibold tracking-widest hover:bg-color-700 focus:bg-color-700 active:bg-color-900 focus:outline-none focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 common-button">
				Log in
			</button>
        </div>
    </form>
</x-guest-layout>
