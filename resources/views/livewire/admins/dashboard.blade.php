<!-- Modern Dashboard with Animations and Stunning Design -->
<div class="min-h-screen bg-gradient-to-br from-zinc-50 via-blue-50 to-indigo-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-indigo-900 p-6">
    <!-- Animated Header Section -->
    <div class="relative overflow-hidden bg-white/80 dark:bg-zinc-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-zinc-700/50 p-8 mb-8">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 via-purple-600/10 to-indigo-600/10 dark:from-blue-400/5 dark:via-purple-400/5 dark:to-indigo-400/5"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-400/20 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-purple-400/20 to-transparent rounded-full blur-3xl"></div>
        
        <div class="relative flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black bg-gradient-to-r from-zinc-900 via-blue-900 to-purple-900 dark:from-white dark:via-blue-100 dark:to-purple-100 bg-clip-text text-transparent">
                            Bursary Dashboard
                        </h1>
                        <p class="text-zinc-600 dark:text-zinc-300 text-lg font-medium">
                            Financial operations center for invoice management
                        </p>
                    </div>
                </div>
            </div>
            <div class="text-right space-y-1">
                <div class="flex items-center space-x-2 justify-end">
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">System Online</span>
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-2xl font-bold text-zinc-700 dark:text-zinc-200">{{ now()->format('g:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Advanced Statistics Cards with Animations -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Invoices Card -->
        <div class="group relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-zinc-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <div class="w-6 h-6 bg-blue-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Total Invoices</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white mb-2">{{ number_format(\App\Models\Invoice::count()) }}</p>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                +{{ \App\Models\Invoice::whereDate('created_at', today())->count() }}
                            </span>
                        </div>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stamped Invoices Card -->
        <div class="group relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-zinc-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-emerald-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Stamped Invoices</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white mb-2">{{ number_format(\App\Models\Invoice::whereNotNull('stamped_at')->count()) }}</p>
                    <div class="flex items-center space-x-2">
                        <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-1000" 
                                 style="width: {{ number_format((\App\Models\Invoice::whereNotNull('stamped_at')->count() / max(\App\Models\Invoice::count(), 1)) * 100, 1) }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-green-600 dark:text-green-400">
                            {{ number_format((\App\Models\Invoice::whereNotNull('stamped_at')->count() / max(\App\Models\Invoice::count(), 1)) * 100, 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Invoices Card -->
        <div class="group relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-zinc-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                            <div class="w-6 h-6 bg-amber-500 rounded-full animate-bounce"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Pending Invoices</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white mb-2">{{ number_format(\App\Models\Invoice::whereNull('stamped_at')->count()) }}</p>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-sm font-bold text-amber-600 dark:text-amber-400">Requires attention</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="group relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-zinc-700/50 p-6 hover:shadow-2xl hover:scale-105 transition-all duration-500">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-600 dark:text-zinc-400 uppercase tracking-wider">Total Users</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white mb-2">{{ number_format(\App\Models\User::count()) }}</p>
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <span class="text-sm font-medium text-purple-600 dark:text-purple-400">
                                {{ \App\Models\User::role('student')->count() }} students
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics & Activity Dashboard -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
        <!-- Real-time Activity Feed -->
        <div class="xl:col-span-2 relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-zinc-700/50">
            <!-- Animated Header -->
            <div class="relative p-6 border-b border-zinc-200/50 dark:border-zinc-700/50">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-pink-500/5"></div>
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Live Activity Stream</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Real-time invoice processing updates</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs font-semibold text-green-600 dark:text-green-400">LIVE</span>
                    </div>
                </div>
            </div>
            
            <!-- Activity List -->
            <div class="p-6 max-h-96 overflow-y-auto">
                <div class="space-y-4">
                    @forelse(\App\Models\Invoice::with(['user', 'stampedBy'])->latest()->take(8)->get() as $invoice)
                        <div class="group relative overflow-hidden bg-zinc-50/80 dark:bg-zinc-700/50 rounded-2xl p-4 hover:bg-zinc-100/80 dark:hover:bg-zinc-700/80 transition-all duration-300 hover:scale-[1.02]">
                            <div class="flex items-center space-x-4">
                                <!-- Status Indicator -->
                                <div class="flex-shrink-0">
                                    @if($invoice->stamped_at)
                                        <div class="relative">
                                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full animate-ping"></div>
                                        </div>
                                    @else
                                        <div class="relative">
                                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-amber-400 rounded-full animate-bounce"></div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Invoice Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-zinc-900 dark:text-white">RRR: {{ $invoice->rrr }}</p>
                                            <p class="text-xs text-zinc-600 dark:text-zinc-400">{{ $invoice->user->name ?? 'Unknown User' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->fee_type === 'school_fees' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' }}">
                                                {{ $invoice->fee_type === 'school_fees' ? 'School Fees' : 'IGR' }}
                                            </span>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                {{ $invoice->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-400 font-medium">No recent activity</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-500">Invoice submissions will appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Advanced Analytics Panel -->
        <div class="space-y-6">
            <!-- Performance Metrics -->
            <div class="relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-zinc-700/50">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 via-purple-500/5 to-pink-500/5"></div>
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Performance</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">System metrics</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Metrics Grid -->
                    <div class="space-y-4">
                        <!-- Processing Rate -->
                        <div class="group p-4 bg-zinc-50/80 dark:bg-zinc-700/50 rounded-2xl hover:bg-zinc-100/80 dark:hover:bg-zinc-700/80 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">Processing Rate</p>
                                        <p class="text-xs text-zinc-600 dark:text-zinc-400">Today's efficiency</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ number_format((\App\Models\Invoice::whereDate('stamped_at', today())->count() / max(\App\Models\Invoice::whereDate('created_at', today())->count(), 1)) * 100, 1) }}%
                                    </p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">completion</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Average Processing Time -->
                        <div class="group p-4 bg-zinc-50/80 dark:bg-zinc-700/50 rounded-2xl hover:bg-zinc-100/80 dark:hover:bg-zinc-700/80 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">Avg. Time</p>
                                        <p class="text-xs text-zinc-600 dark:text-zinc-400">Per invoice</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">2.3h</p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">processing</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Health -->
                        <div class="group p-4 bg-zinc-50/80 dark:bg-zinc-700/50 rounded-2xl hover:bg-zinc-100/80 dark:hover:bg-zinc-700/80 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">System Health</p>
                                        <p class="text-xs text-zinc-600 dark:text-zinc-400">All systems</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                        <p class="text-sm font-bold text-green-600 dark:text-green-400">Optimal</p>
                                    </div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">99.9% uptime</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="relative overflow-hidden bg-white/90 dark:bg-zinc-800/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-zinc-700/50">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-teal-500/5 to-cyan-500/5"></div>
                <div class="relative p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Quick Stats</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">At a glance</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-zinc-50/80 dark:bg-zinc-700/50 rounded-xl">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Available Stamps</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ \App\Models\Stamp::count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-zinc-50/80 dark:bg-zinc-700/50 rounded-xl">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Active Schools</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ \App\Models\School::count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-zinc-50/80 dark:bg-zinc-700/50 rounded-xl">
                            <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Today's Activity</span>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ \App\Models\Invoice::whereDate('created_at', today())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Type Distribution -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Invoice Distribution</h3>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">Breakdown by fee type and status</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School Fees -->
                <div class="text-center">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">School Fees</h4>
                    <div class="space-y-2">
                        @php
                            $schoolFeesTotal = \App\Models\Invoice::where('fee_type', 'school_fees')->count();
                            $schoolFeesStamped = \App\Models\Invoice::where('fee_type', 'school_fees')->whereNotNull('stamped_at')->count();
                            $schoolFeesPending = $schoolFeesTotal - $schoolFeesStamped;
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Total:</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $schoolFeesTotal }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-green-600 dark:text-green-400">Stamped:</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $schoolFeesStamped }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-yellow-600 dark:text-yellow-400">Pending:</span>
                            <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">{{ $schoolFeesPending }}</span>
                        </div>
                    </div>
                </div>

                <!-- IGR -->
                <div class="text-center">
                    <h4 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">IGR</h4>
                    <div class="space-y-2">
                        @php
                            $igrTotal = \App\Models\Invoice::where('fee_type', 'igr')->count();
                            $igrStamped = \App\Models\Invoice::where('fee_type', 'igr')->whereNotNull('stamped_at')->count();
                            $igrPending = $igrTotal - $igrStamped;
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">Total:</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $igrTotal }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-green-600 dark:text-green-400">Stamped:</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ $igrStamped }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-yellow-600 dark:text-yellow-400">Pending:</span>
                            <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">{{ $igrPending }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
