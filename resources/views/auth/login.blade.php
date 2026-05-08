<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-4xl font-bold text-gray-900 tracking-tight">Login</h2>
        <p class="text-gray-500 mt-3 text-lg font-medium">Welcome back! Please enter your details.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2 group">
            <x-input-label for="email" :value="__('Email Address')" class="group-focus-within:text-green-600 transition-all font-semibold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500 transition-colors">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-11 py-3.5 border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-2xl shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2 group">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="group-focus-within:text-green-600 transition-all font-semibold" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-green-600 hover:text-green-700 font-bold transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500 transition-colors">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <x-text-input id="password" class="block w-full pl-11 py-3.5 border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-2xl shadow-sm"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 transition-all cursor-pointer" name="remember">
                <span class="ms-3 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">{{ __('Keep me logged in') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-4 text-base rounded-2xl shadow-lg shadow-green-200 hover:shadow-green-300 transition-all transform hover:-translate-y-1">
                {{ __('Sign in') }}
            </x-primary-button>
            
            @if (Route::has('register'))
                <p class="mt-8 text-center text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-bold text-green-600 hover:text-green-700 transition-all hover:underline decoration-2 underline-offset-4">Create account</a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
