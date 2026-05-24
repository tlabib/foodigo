<x-guest-layout>
    <x-auth-session-status class="mb-4 rounded-lg bg-[#ffe3df] px-3 py-2 text-sm text-[#F15B4E]" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#F15B4E] shadow-sm focus:ring-[#F15B4E]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-5">
            <x-primary-button class="w-full justify-center !bg-[#F15B4E] hover:!bg-[#e44f42] focus:!ring-[#F15B4E] py-3 text-sm tracking-wide">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-4 flex items-center justify-center gap-3 text-sm">
            @if (Route::has('register'))
                <a class="inline-flex items-center rounded-full border border-[#F15B4E] px-3 py-1.5 text-[#F15B4E] font-medium hover:bg-[#fff0ee] transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F15B4E]" href="{{ route('register') }}">
                    {{ __('Need an account? Register') }}
                </a>
            @endif

            @if (Route::has('password.request'))
                <a class="text-gray-600 hover:text-[#F15B4E] underline underline-offset-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F15B4E]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
