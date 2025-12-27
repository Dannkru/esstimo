<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Estimo') }} - Kalkulator Wycen Budowlanych</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-16">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <span class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">Estimo</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    @if (Route::has('login') || Route::has('register'))
                        <div class="hidden md:flex items-center space-x-3 lg:space-x-4">
                            @auth
                                @if (Route::has('dashboard'))
                                    <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                        Moje Wyceny
                                    </a>
                                @endif
                            @else
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                        Zaloguj się
                                    </a>
                                @endif
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors active:bg-indigo-800">
                                        Zarejestruj się
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <!-- Mobile Menu Button -->
                        <div class="md:hidden">
                            <button 
                                type="button" 
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors"
                                onclick="toggleMobileMenu()"
                                aria-expanded="false"
                                aria-label="Toggle menu"
                            >
                                <svg id="menu-icon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg id="close-icon" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Mobile Menu -->
                @if (Route::has('login') || Route::has('register'))
                    <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200 dark:border-gray-700 mt-2">
                        <div class="flex flex-col space-y-2 pt-4">
                            @auth
                                @if (Route::has('dashboard'))
                                    <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-base font-medium transition-colors active:bg-gray-100 dark:active:bg-gray-700">
                                        Moje Wyceny
                                    </a>
                                @endif
                            @else
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 px-3 py-2 rounded-md text-base font-medium transition-colors active:bg-gray-100 dark:active:bg-gray-700">
                                        Zaloguj się
                                    </a>
                                @endif
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-base font-medium transition-colors active:bg-indigo-800 text-center">
                                        Zarejestruj się
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-8 sm:mt-12">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
                <p class="text-center text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} Estimo. Kalkulator wycen budowlanych.
                </p>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button[onclick="toggleMobileMenu()"]');
            
            if (!button && !menu.contains(event.target) && !menu.classList.contains('hidden')) {
                toggleMobileMenu();
            }
        });
    </script>

    @livewireScripts
</body>
</html>

