<div class="relative">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-neutral-950 dark:to-neutral-900">
            <!-- Background Gradient -->
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl mb-15">
        <!-- Greeting Section -->
        <div class="mb-6 p-6 bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <div class="animate-wave text-4xl">👋</div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        @php
                            $hour = date('H');
                            $greeting = 'Good morning';
                            if($hour >= 12 && $hour < 17) {
                                $greeting = 'Good afternoon';
                            } elseif($hour >= 17) {
                                $greeting = 'Good evening';
                            }
                            $firstName = explode(' ', auth()->user()->name)[0];
                        @endphp
                        {{ $greeting }}, {{ $firstName }}!
                    </h1>
                </div>
            </div>
            <div>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    Welcome to your dashboard. Here you can manage and get your invoices stamped easily.
                    {{ collect([
                        'Ready to get your documents processed?',
                        'Let\'s make your invoice stamping hassle-free!',
                        'Quick and easy invoice stamping awaits!'
                    ])->random() }}
                </p>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <a href="{{ route('student.pending.invoices') }}" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 transition-all hover:shadow-lg hover:shadow-yellow-500/20 hover:border-yellow-500/50 cursor-pointer">
                <div class="absolute inset-0 flex flex-col justify-center items-center p-4">
                    <svg class="w-12 h-12 text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">Pending Invoices</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-2">View your pending invoice documents</p>
                </div>
            </a>

            <a href="{{ route('student.stamped-documents') }}" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 transition-all hover:shadow-lg hover:shadow-green-500/20 hover:border-green-500/50 cursor-pointer">
                <div class="absolute inset-0 flex flex-col justify-center items-center p-4">
                    <svg class="w-12 h-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">Stamped Documents</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-2">View your processed documents</p>
                </div>
            </a>

            <a href="{{ route('invoice.upload') }}" class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 transition-all hover:shadow-lg hover:shadow-green-500/20 hover:border-yellow-500/50">
                <div class="absolute inset-0 flex flex-col justify-center items-center p-4">
                    <svg class="w-12 h-12 text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <div class="text-xl font-bold text-gray-900 dark:text-white">Upload New Invoice</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-2">Quickly upload a new invoice</p>
                </div>
            </a>
        </div>

        <!-- Activity Section -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Recent Stamped</h2>
            <div class="space-y-4">
                <p class="text-gray-500 dark:text-gray-400">No recent stamped to display</p>
            </div>
        </div>
    </div>

    <style>
        .animate-wave {
            animation: wave 2s infinite;
            transform-origin: 70% 70%;
            display: inline-block;
        }

        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
    </style>
</div>