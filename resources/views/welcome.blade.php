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
        <style>
            .glassmorphism {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .dark .glassmorphism {
                background: rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .gradient-text {
                background: linear-gradient(135deg, #10b981, #059669, #047857);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .floating-animation {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .pulse-glow {
                animation: pulse-glow 2s infinite;
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
                50% { box-shadow: 0 0 40px rgba(16, 185, 129, 0.6); }
            }
        </style>
    </head>
    <body class="antialiased bg-gradient-to-br from-slate-50 via-white to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-emerald-900 min-h-screen overflow-x-hidden">
        <!-- Enhanced Navigation -->
        <nav class="fixed w-full glassmorphism z-50 border-b border-white/20 dark:border-gray-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <img src="{{ asset('assets/logo.png') }}" alt="Stampit Logo" class="h-12 w-12 rounded-xl shadow-lg floating-animation">
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-xl opacity-20 blur-sm"></div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold gradient-text">Stampit</h1>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Digital Excellence</p>
                        </div>
                    </div>
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                @role('super admin')
                                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg pulse-glow">
                                        Dashboard
                                    </a>
                                @endrole
                                @role('student')
                                    <a href="{{ route('student.dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg pulse-glow">
                                        Dashboard
                                    </a>
                                @endrole
                                @hasanyrole('school fees admin|igrs admin')
                                    <a href="{{ route('admins.dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg pulse-glow">
                                        Dashboard
                                    </a>
                                @endhasanyrole
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-300">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <main class="pt-20">
            <!-- Enhanced Hero Section -->
            <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
                <!-- Background Elements -->
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 via-white/30 to-emerald-100/50 dark:from-emerald-900/20 dark:via-gray-800/50 dark:to-emerald-800/30"></div>
                <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-300/20 rounded-full blur-3xl floating-animation"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl floating-animation" style="animation-delay: -3s;"></div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div class="space-y-8">
                        <!-- Main Heading -->
                        <div class="space-y-4">
                            <h1 class="text-5xl sm:text-7xl lg:text-8xl font-bold leading-tight">
                                <span class="block text-gray-900 dark:text-white">Welcome to</span>
                                <span class="block gradient-text">Stampit</span>
                            </h1>
                            <div class="w-32 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 mx-auto rounded-full"></div>
                        </div>

                        <!-- Subtitle -->
                        <p class="text-xl sm:text-2xl lg:text-3xl text-gray-600 dark:text-gray-300 max-w-4xl mx-auto leading-relaxed">
                            The <span class="font-semibold text-emerald-600 dark:text-emerald-400">official digital document management system</span> for 
                            <span class="font-semibold">Bamidele Olumilua University of Education, Science and Technology, Ikere-Ekiti (BOUESTI)</span>
                        </p>

                        <!-- Enhanced CTA Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-8">
                            @auth
                                @role('super admin')
                                    <a href="{{ route('dashboard') }}" class="group relative px-12 py-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xl font-bold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-2xl pulse-glow">
                                        <span class="relative z-10">Access Dashboard</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </a>
                                @endrole
                                @role('student')
                                    <a href="{{ route('student.dashboard') }}" class="group relative px-12 py-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xl font-bold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-2xl pulse-glow">
                                        <span class="relative z-10">Access Dashboard</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </a>
                                @endrole
                                @hasanyrole('school fees admin|igrs admin')
                                    <a href="{{ route('admins.dashboard') }}" class="group relative px-12 py-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xl font-bold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-2xl pulse-glow">
                                        <span class="relative z-10">Access Dashboard</span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </a>
                                @endhasanyrole
                            @else
                                <a href="{{ route('register') }}" class="group relative px-12 py-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xl font-bold rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-2xl pulse-glow">
                                    <span class="relative z-10">Get Started Now</span>
                                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </a>
                                <a href="{{ route('login') }}" class="group px-12 py-6 glassmorphism text-gray-900 dark:text-white text-xl font-bold rounded-2xl hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 transition-all duration-300 shadow-xl">
                                    Sign In to Your Account
                                </a>
                            @endauth
                        </div>

                        <!-- Stats Section -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 pt-16 max-w-4xl mx-auto">
                            <div class="glassmorphism rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                                <div class="text-3xl font-bold gradient-text">99.9%</div>
                                <div class="text-gray-600 dark:text-gray-300 font-medium">Uptime</div>
                            </div>
                            <div class="glassmorphism rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                                <div class="text-3xl font-bold gradient-text">24/7</div>
                                <div class="text-gray-600 dark:text-gray-300 font-medium">Support</div>
                            </div>
                            <div class="glassmorphism rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                                <div class="text-3xl font-bold gradient-text">Secure</div>
                                <div class="text-gray-600 dark:text-gray-300 font-medium">Processing</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Enhanced Features Section -->
            <section class="py-32 bg-gradient-to-b from-white to-emerald-50/50 dark:from-gray-800 dark:to-emerald-900/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="text-center mb-20">
                        <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                            Why Choose <span class="gradient-text">Stampit?</span>
                        </h2>
                        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                            Experience the future of document management with our cutting-edge features designed for modern educational institutions.
                        </p>
                        <div class="w-24 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 mx-auto mt-8 rounded-full"></div>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="group glassmorphism rounded-3xl p-8 hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 hover:-translate-y-2 transition-all duration-500">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-emerald-400 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-300">
                                Secure Verification
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                Advanced digital verification system with blockchain-inspired security ensuring the authenticity of every Remita receipt processed through our platform.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="group glassmorphism rounded-3xl p-8 hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 hover:-translate-y-2 transition-all duration-500">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-400 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                Lightning Fast
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                Streamlined workflow powered by modern technology that reduces document processing time from days to minutes with real-time updates.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="group glassmorphism rounded-3xl p-8 hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 hover:-translate-y-2 transition-all duration-500">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-purple-400 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">
                                Enterprise Security
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                Bank-grade security with role-based access control, comprehensive audit trails, and end-to-end encryption for all sensitive data.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="group glassmorphism rounded-3xl p-8 hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 hover:-translate-y-2 transition-all duration-500">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-orange-400 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-300">
                                Smart Analytics
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                Comprehensive dashboard with real-time analytics, detailed reporting, and insights to help administrators make data-driven decisions.
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="group glassmorphism rounded-3xl p-8 hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 hover:-translate-y-2 transition-all duration-500">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-pink-400 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors duration-300">
                                Mobile Ready
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                Fully responsive design that works seamlessly across all devices, ensuring accessibility for students and staff anywhere, anytime.
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="group glassmorphism rounded-3xl p-8 hover:bg-white/20 dark:hover:bg-black/30 transform hover:scale-105 hover:-translate-y-2 transition-all duration-500">
                            <div class="relative mb-6">
                                <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-indigo-400 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">
                                Multi-Role Support
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                Designed for students, bursary officers, and administrators with customized interfaces and permissions for each user type.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Enhanced Footer -->
            <footer class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <div class="flex items-center justify-center space-x-3 mb-6">
                            <img src="{{ asset('assets/logo.png') }}" alt="Stampit Logo" class="h-12 w-12 rounded-xl">
                            <h3 class="text-3xl font-bold gradient-text">Stampit</h3>
                        </div>
                        <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                            Empowering BOUESTI with cutting-edge digital document management solutions for a more efficient and secure future.
                        </p>
                        <div class="w-24 h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 mx-auto rounded-full mb-8"></div>
                        <p class="text-gray-400">
                            © {{ date('Y') }} Stampit. Built with ❤️ for Bamidele Olumilua University of Education, Science and Technology, Ikere-Ekiti.
                        </p>
                    </div>
                </div>
            </footer>
        </main>
    </body>
</html>
