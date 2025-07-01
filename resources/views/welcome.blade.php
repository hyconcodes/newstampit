<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stampit - Digital Document Management</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css'])
    </head>
    <body class="antialiased bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <nav class="fixed w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm z-50 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <img src="{{ asset('assets/stamp.jpg') }}" alt="Stampit Logo" class="h-10 rounded w-auto">
                    </div>
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <main class="pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <section class="py-20 sm:py-32">
                    <div class="text-center">
                        <h1 class="text-4xl sm:text-6xl font-bold text-gray-900 dark:text-white">
                            Welcome to <span class="text-green-600 dark:text-green-400">Stampit</span>
                        </h1>
                        <p class="mt-6 text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                            The official digital document management system for Bamidele Olumilua University of Education, Science and Technology, Ikere-Ekiti (BOUESTI)
                        </p>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="group hover:scale-105 transition duration-300 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md">
                            <div class="inline-flex items-center justify-center p-3 bg-green-100 dark:bg-green-900/50 rounded-xl">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Secure Verification</h3>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">Advanced digital verification system ensuring the authenticity of every Remita receipt processed.</p>
                        </div>

                        <div class="group hover:scale-105 transition duration-300 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md">
                            <div class="inline-flex items-center justify-center p-3 bg-green-100 dark:bg-green-900/50 rounded-xl">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Fast Processing</h3>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">Streamlined workflow that reduces document processing time from days to minutes.</p>
                        </div>

                        <div class="group hover:scale-105 transition duration-300 p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md">
                            <div class="inline-flex items-center justify-center p-3 bg-green-100 dark:bg-green-900/50 rounded-xl">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Secure Access</h3>
                            <p class="mt-4 text-gray-600 dark:text-gray-300">Enterprise-grade security with role-based access control and comprehensive audit trails.</p>
                        </div>
                    </div>

                    <div class="mt-16 flex flex-col sm:flex-row items-center justify-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-green-600 text-white text-center font-semibold rounded-xl hover:bg-green-700 transform transition focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Access Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-green-600 text-white text-center font-semibold rounded-xl hover:bg-green-700 transform transition focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Get Started Now
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-center font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transform transition border border-gray-200 dark:border-gray-700">
                                Sign In to Your Account
                            </a>
                        @endauth
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
