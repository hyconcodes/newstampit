<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-zinc-600 dark:text-zinc-400 mt-1">Welcome back! Here's what's happening with your invoice stamping system.</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ now()->format('l, F j, Y') }}</p>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ now()->format('g:i A') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Invoices -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Invoices</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\Invoice::count() }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 dark:text-green-400">
                    +{{ \App\Models\Invoice::whereDate('created_at', today())->count() }} today
                </span>
            </div>
        </div>

        <!-- Stamped Invoices -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Stamped Invoices</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\Invoice::whereNotNull('stamped_at')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 dark:text-green-400">
                    {{ number_format((\App\Models\Invoice::whereNotNull('stamped_at')->count() / max(\App\Models\Invoice::count(), 1)) * 100, 1) }}% completion rate
                </span>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Pending Invoices</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\Invoice::whereNull('stamped_at')->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600 dark:text-yellow-400">
                    Requires attention
                </span>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Users</p>
                    <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-purple-600 dark:text-purple-400">
                    {{ \App\Models\User::role('student')->count() }} students
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Invoices -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Recent Invoices</h3>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">Latest invoice submissions and their status</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse(\App\Models\Invoice::with(['user', 'stampedBy'])->latest()->take(5)->get() as $invoice)
                        <div class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($invoice->stamped_at)
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">RRR: {{ $invoice->rrr }}</p>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-400">{{ $invoice->user->name ?? 'Unknown User' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                    {{ $invoice->fee_type === 'school_fees' ? 'School Fees' : 'IGR' }}
                                </p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400">
                                    {{ $invoice->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-zinc-400 dark:text-zinc-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-zinc-600 dark:text-zinc-400">No invoices found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & System Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('stamp.school.fees.invoices')
                        <a href="{{ route('stamp.igrs.invoices') }}" class="flex items-center p-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Manage Invoices
                        </a>
                    @endcan
                    
                    @can('stamp.igr.invoices')
                        <a href="{{ route('stamp.igrs.invoices') }}" class="flex items-center p-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            IGR Invoices
                        </a>
                    @endcan

                    @role('super admin')
                        <a href="{{ route('admin.stamps') }}" class="flex items-center p-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                            <svg class="w-5 h-5 mr-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Manage Stamps
                        </a>
                    @endrole

                    <a href="{{ route('students') }}" class="flex items-center p-3 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-700/50 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                        <svg class="w-5 h-5 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Manage Users
                    </a>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">System Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Available Stamps</span>
                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ \App\Models\Stamp::count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Active Schools</span>
                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ \App\Models\School::count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Today's Activity</span>
                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ \App\Models\Invoice::whereDate('created_at', today())->count() }} invoices</span>
                    </div>
                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-sm text-zinc-600 dark:text-zinc-400">System Online</span>
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
