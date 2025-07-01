<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stampit - Digital Document Management</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="antialiased bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end pt-6">
                    @if (Route::has('login'))
                        <div class="space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-indigo-500">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <div class="flex flex-col items-center justify-center mt-16">
                    <div class="text-center">
                        <h1 class="text-6xl font-bold text-gray-900 dark:text-white">
                            <span class="text-indigo-600 dark:text-indigo-400">Stampit</span>
                        </h1>
                        <p class="mt-4 text-xl text-gray-600 dark:text-gray-300">Digital Document Management for BOUESTI</p>
                    </div>

                    <div class="mt-8 flex justify-center">
                        <img src="{{ asset('assets/stamp.jpg') }}" alt="Stamp" class="w-48 h-48 object-contain">
                    </div>

                    <div class="mt-12 max-w-3xl text-center">
                        <p class="text-lg text-gray-600 dark:text-gray-300">
                            Stampit is a secure and efficient digital solution for automating the stamping process of Remita invoices at Bamidele Olumilua University of Education, Science and Technology, Ikere-Ekiti (BOUESTI).
                        </p>
                    </div>

                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-indigo-500 dark:text-indigo-400 text-2xl mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Secure Verification</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">Robust digital verification system for Remita receipts</p>
                        </div>

                        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-indigo-500 dark:text-indigo-400 text-2xl mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Fast Processing</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">Automated stamping workflow for quick turnaround</p>
                        </div>

                        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <div class="text-indigo-500 dark:text-indigo-400 text-2xl mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Secure Access</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">Role-based permissions and audit trails</p>
                        </div>
                    </div>

                    <div class="mt-12 flex space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" class="px-6 py-3 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-white font-semibold rounded-lg shadow-sm transition">
                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
