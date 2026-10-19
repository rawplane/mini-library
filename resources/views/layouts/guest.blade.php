<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MiniLibrary') }} - Masuk / Daftar</title>

        <!-- Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Inline script to prevent theme flashing -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body
        x-data="{
            darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }"
        class="h-full font-sans antialiased bg-slate-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100"
    >
        <div class="min-h-screen flex flex-col lg:flex-row">
            <!-- Left Branding Pane (Split-Screen) -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-700 via-indigo-600 to-indigo-900 text-white p-12 flex-col justify-between overflow-hidden">
                <!-- Background decoration shapes -->
                <div class="absolute -right-20 -top-20 w-96 h-96 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-96 h-96 rounded-full bg-purple-500/20 blur-3xl pointer-events-none"></div>

                <!-- Top Brand -->
                <div class="relative z-10 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-2xl tracking-tight">MiniLibrary</span>
                </div>

                <!-- Middle Feature Statement -->
                <div class="relative z-10 max-w-md my-auto py-12">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-medium text-indigo-100 mb-6">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Peminjaman Buku Digital
                    </div>
                    <h1 class="text-4xl font-extrabold tracking-tight leading-tight mb-4">
                        Akses Literasi Lebih Cepat, Teratur, & Transparan.
                    </h1>
                    <p class="text-indigo-100/80 text-base leading-relaxed mb-8">
                        Jelajahi puluhan koleksi buku, lakukan reservasi mandiri, pantau jadwal jatuh tempo dan status denda berjalan secara real-time.
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-white/10 backdrop-blur-md border border-white/15">
                            <div class="text-2xl font-bold">7 Hari</div>
                            <div class="text-xs text-indigo-200 mt-0.5">Durasi Pinjam Standar</div>
                        </div>
                        <div class="p-4 rounded-xl bg-white/10 backdrop-blur-md border border-white/15">
                            <div class="text-2xl font-bold">Maks. 3</div>
                            <div class="text-xs text-indigo-200 mt-0.5">Buku Aktif per Anggota</div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Quote / Footer -->
                <div class="relative z-10 text-xs text-indigo-200/70 border-t border-white/10 pt-4 flex justify-between items-center">
                    <span>&copy; {{ date('Y') }} MiniLibrary Platform.</span>
                    <span>Laravel 11 & Tailwind CSS</span>
                </div>
            </div>

            <!-- Right Form Pane -->
            <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-12 relative overflow-y-auto">
                <!-- Top Nav inside Form Side -->
                <div class="flex items-center justify-between mb-8">
                    <!-- Mobile Brand Display -->
                    <div class="lg:hidden flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-lg text-gray-900 dark:text-white">MiniLibrary</span>
                    </div>

                    <div class="ml-auto">
                        <!-- Dark Mode Toggle Button -->
                        <button
                            @click="toggleDarkMode()"
                            type="button"
                            class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors focus:outline-none border border-gray-200 dark:border-gray-700/60"
                            aria-label="Toggle Theme"
                        >
                            <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Form Center -->
                <div class="max-w-md w-full mx-auto my-auto py-4">
                    {{ $slot }}
                </div>

                <!-- Bottom Helper -->
                <div class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500">
                    Sistem Otomasi Perpustakaan &copy; {{ date('Y') }}
                </div>
            </div>
        </div>
    </body>
</html>
