<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    
    <div class="max-w-md mx-auto bg-white dark:bg-slate-800 p-8 rounded-2xl ">
        <div class="flex justify-center mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">
            Login
        </h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="relative">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password"
                    class="block mt-1 w-full pr-10"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password" />
                

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Show Password Toggle -->
            <div class="flex items-center mt-2">
                <label for="show_password" class="inline-flex items-center cursor-pointer select-none">
                    <input id="show_password" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Tampilkan password</span>
                </label>
            </div>

            <!-- Aksi -->
            <div class="flex flex-col sm:flex-row items-center justify-between mt-6 gap-4">
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif

                <x-primary-button class="w-full sm:w-auto justify-center">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        // Toggle via checkbox
        document.getElementById('show_password').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (this.checked) {
                passwordField.type = 'text';
                icon.outerHTML = `<svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                    a9.964 9.964 0 012.112-3.592m3.938-2.29A9.955 9.955 0 0112 5c4.477 0
                    8.268 2.943 9.542 7a9.953 9.953 0 01-4.31 5.185M15 12a3 3 0
                    11-6 0 3 3 0 016 0z" /></svg>`;
            } else {
                passwordField.type = 'password';
                icon.outerHTML = `<svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                    -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    <circle cx="12" cy="12" r="3" /></svg>`;
            }
        });
    </script>
</x-guest-layout>
