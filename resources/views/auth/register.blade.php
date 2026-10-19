<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Daftar Akun Anggota</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Bergabung untuk mulai meminjam buku dan menjelajahi katalog perpustakaan.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input
                id="name"
                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Contoh: Budi Santoso"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input
                id="email"
                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="nama@email.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input
                id="password"
                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Ulangi kata sandi"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button class="w-full py-2.5 text-base">
                Daftar Sebagai Anggota
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
            Masuk sekarang
        </a>
    </div>
</x-guest-layout>
