<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class'
            }
        </script>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white dark:bg-[#161615] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-xl font-semibold">
                                {{ config('app.name', 'Laravel') }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">
                                Home
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">
                                Login
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-gray-100 dark:hover:bg-[#3E3E3A]">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow">
            @yield('content')
        </main>
    </div>
</body>
</html>
