<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-base-white font-sans p-4">
        <div class="max-w-md w-full bg-white border border-gray-100 rounded-xl shadow-md p-8">
            
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-soft-green mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-base-dark tracking-tight">Saung Aqiqah</h2>
                <p class="text-sm text-base-medium mt-1">Sistem Pendukung Keputusan TOPSIS</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email / Username -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email atau Username</label>
                    <input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-2 px-3 transition-colors" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-primary hover:text-primary-dark transition-colors" href="{{ route('password.request') }}">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm py-2 px-3 transition-colors" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
