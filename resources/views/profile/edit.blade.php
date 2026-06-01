<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-dark leading-tight">
            {{ __('Profil Anda') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Update Profile Information -->
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-base-dark">
                                Informasi Profil
                            </h2>
                            <p class="mt-1 text-sm text-base-medium">
                                Perbarui informasi profil dan alamat email akun Anda.
                            </p>
                        </header>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name" class="block text-sm font-medium text-base-dark mb-1">Nama</label>
                                <input id="name" name="name" type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-base-dark mb-1">Email</label>
                                <input id="email" name="email" type="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-medium text-sm text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors shadow-sm">
                                    Simpan Perubahan
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >Tersimpan.</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-xl">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-base-dark">
                                Perbarui Password
                            </h2>
                            <p class="mt-1 text-sm text-base-medium">
                                Pastikan akun Anda menggunakan kata sandi acak yang panjang agar tetap aman.
                            </p>
                        </header>

                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="update_password_current_password" class="block text-sm font-medium text-base-dark mb-1">Password Saat Ini</label>
                                <input id="update_password_current_password" name="current_password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <label for="update_password_password" class="block text-sm font-medium text-base-dark mb-1">Password Baru</label>
                                <input id="update_password_password" name="password" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <label for="update_password_password_confirmation" class="block text-sm font-medium text-base-dark mb-1">Konfirmasi Password</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm transition-colors py-2 px-3" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-medium text-sm text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors shadow-sm">
                                    Simpan Password
                                </button>

                                @if (session('status') === 'password-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >Tersimpan.</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
