<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-4xl font-bold text-gray-900 tracking-tight">Forgot Password</h2>
        <p class="text-gray-500 mt-3 text-lg font-medium">No problem. Just let us know your email address and we will email you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2 group">
            <x-input-label for="email" :value="__('Email Address')" class="group-focus-within:text-green-600 transition-all font-semibold" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-green-500 transition-colors">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-11 py-3.5 border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-2xl shadow-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-4 text-base rounded-2xl shadow-lg shadow-green-200 hover:shadow-green-300 transition-all transform hover:-translate-y-1">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
            
            <p class="mt-8 text-center text-gray-600">
                Remember your password? 
                <a href="{{ route('login') }}" class="font-bold text-green-600 hover:text-green-700 transition-all hover:underline decoration-2 underline-offset-4">Back to login</a>
            </p>
        </div>
    </form>
</x-guest-layout>
