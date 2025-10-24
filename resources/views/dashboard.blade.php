<x-layouts.app :title="__('Dashboard')">
    @php
        $startDate = now()->subDays(29)->startOfDay();

        $registrations = \App\Models\User::whereNotNull('email_verified_at')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $invoices = \App\Models\Invoice::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $registrationSeries = [];
        $invoiceSeries = [];
        for ($i = 0; $i < 30; $i++) {
            $day = now()
                ->subDays(29 - $i)
                ->toDateString();
            $labels[] = $day;
            $registrationSeries[] = isset($registrations[$day]) ? (int) $registrations[$day]->count : 0;
            $invoiceSeries[] = isset($invoices[$day]) ? (int) $invoices[$day]->count : 0;
        }
    @endphp

    <!-- Modern Dashboard Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl mb-8">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/3 rounded-full blur-3xl animate-pulse delay-1000"></div>
        
        <div class="relative p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center shadow-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Super Admin Dashboard</h1>
                        <p class="text-white/80 text-lg">Complete system overview and analytics</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-xl rounded-full px-4 py-2">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-white font-semibold text-sm">System Online</span>
                    </div>
                    <div class="text-right text-white">
                        <p class="text-sm opacity-80">{{ now()->format('l, F j, Y') }}</p>
                        <p class="text-lg font-semibold">{{ now()->format('g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="min-h-screen space-y-8">
        <!-- System Overview Section -->
        <div class="grid grid-cols-1 gap-8">
@php
    $bursaryOfficersCount = \Spatie\Permission\Models\Role::findByName('school fees admin')->users()->count() + 
                           \Spatie\Permission\Models\Role::findByName('igrs admin')->users()->count();
    
    $roles = [
        'bursary officers' => [
            'count' => $bursaryOfficersCount,
            'color' => '#16a34a',
            'bg'    => 'rgba(22,163,74,0.15)'
        ],
        'student' => [
            'count' => \Spatie\Permission\Models\Role::findByName('student')->users()->count(),
            'color' => '#10b981',
            'bg'    => 'rgba(16,185,129,0.15)'
        ]
    ];
    $total = collect($roles)->sum('count');
@endphp
<!-- Enhanced System Administrators Overview -->
<div class="relative group">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 via-purple-500/20 to-pink-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 p-8 hover:shadow-3xl transition-all duration-500 group-hover:scale-[1.02]">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">System Administrators</h2>
                    <p class="text-gray-600 dark:text-gray-300">Role distribution overview</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 bg-gradient-to-r from-green-500/20 to-emerald-500/20 backdrop-blur-xl rounded-full px-4 py-2 border border-green-500/30">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-green-700 dark:text-green-400 font-semibold text-sm">{{ $total }} Total</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Role Statistics Cards -->
            <div class="space-y-4">
                @foreach($roles as $label => $role)
                    <div class="group/card relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-purple-600/10 rounded-2xl group-hover/card:from-blue-500/20 group-hover/card:to-purple-600/20 transition-all duration-300"></div>
                        <div class="relative bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-neutral-200/50 dark:border-neutral-700/50 group-hover/card:shadow-lg group-hover/card:scale-[1.02] transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white capitalize">
                                            @if($label === 'bursary officers')
                                                Bursary Officers
                                            @else
                                                {{ ucfirst($label) }}
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            @if($label === 'bursary officers')
                                                Financial administrators
                                            @else
                                                Active users
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $role['count'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format(($role['count'] / $total) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mt-4">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-1000 ease-out" 
                                         style="width: {{ ($role['count'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="group/card relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-emerald-600/10 rounded-2xl group-hover/card:from-green-500/20 group-hover/card:to-emerald-600/20 transition-all duration-300"></div>
                    <div class="relative bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-green-200/50 dark:border-green-700/50 group-hover/card:shadow-lg group-hover/card:scale-[1.02] transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Chart Container -->
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-gray-100/50 dark:from-gray-800/50 dark:to-gray-900/50 rounded-2xl"></div>
                <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribution Chart</h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span>Live Data</span>
                        </div>
                    </div>
                    <div class="relative aspect-square w-full max-w-xs mx-auto">
                        <canvas id="rolesPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('rolesPieChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($roles)),
            datasets: [{
                data: @json(array_column($roles,'count')),
                backgroundColor: @json(array_column($roles,'color'))
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const label = ctx.label === 'bursary officers' ? 'BURSARY OFFICERS' : ctx.label.toUpperCase();
                            return `${label}: ${ctx.parsed}`;
                        }
                    }
                }
            }
        }
    });
});
</script>
            <!-- Enhanced Student Registrations Chart -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-teal-500/20 to-cyan-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
                <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 p-8 hover:shadow-3xl transition-all duration-500 group-hover:scale-[1.02]">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Student Registrations</h2>
                                <p class="text-gray-600 dark:text-gray-300">Last 30 days trend analysis</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2 bg-gradient-to-r from-emerald-500/20 to-teal-500/20 backdrop-blur-xl rounded-full px-4 py-2 border border-emerald-500/30">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <span class="text-emerald-700 dark:text-emerald-400 font-semibold text-sm">{{ array_sum($registrationSeries) }} Total</span>
                            </div>
                            <div class="text-right text-gray-600 dark:text-gray-300">
                                <p class="text-sm">Peak: {{ max($registrationSeries) }}</p>
                                <p class="text-xs opacity-75">Avg: {{ number_format(array_sum($registrationSeries) / count($registrationSeries), 1) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-gray-100/50 dark:from-gray-800/50 dark:to-gray-900/50 rounded-2xl"></div>
                        <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                            <div class="h-80">
                                <canvas id="registrationsChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Enhanced Invoice Uploads Chart -->
        <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 via-red-500/20 to-pink-500/20 rounded-3xl blur-xl group-hover:blur-2xl transition-all duration-500"></div>
            <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/20 p-8 hover:shadow-3xl transition-all duration-500 group-hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Invoice Uploads</h2>
                            <p class="text-gray-600 dark:text-gray-300">Last 30 days activity tracking</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2 bg-gradient-to-r from-orange-500/20 to-red-500/20 backdrop-blur-xl rounded-full px-4 py-2 border border-orange-500/30">
                            <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                            <span class="text-orange-700 dark:text-orange-400 font-semibold text-sm">{{ array_sum($invoiceSeries) }} Total</span>
                        </div>
                        <div class="text-right text-gray-600 dark:text-gray-300">
                            <p class="text-sm">Peak: {{ max($invoiceSeries) }}</p>
                            <p class="text-xs opacity-75">Avg: {{ number_format(array_sum($invoiceSeries) / count($invoiceSeries), 1) }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-gray-100/50 dark:from-gray-800/50 dark:to-gray-900/50 rounded-2xl"></div>
                    <div class="relative bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                        <div class="h-80">
                            <canvas id="invoicesChart" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($labels ?? []);
        const registrations = @json($registrationSeries ?? []);
        const invoices = @json($invoiceSeries ?? []);

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 6
                    }
                },
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        };

        let registrationsChartInstance;
        let invoicesChartInstance;

        function initCharts() {
            const regCanvas = document.getElementById('registrationsChart');
            const invCanvas = document.getElementById('invoicesChart');

            if (regCanvas) {
                const ctxReg = regCanvas.getContext('2d');
                if (registrationsChartInstance) {
                    registrationsChartInstance.destroy();
                }
                registrationsChartInstance = new Chart(ctxReg, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Registrations',
                            data: registrations,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22,163,74,0.15)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2
                        }]
                    },
                    options: commonOptions
                });
            }

            if (invCanvas) {
                const ctxInv = invCanvas.getContext('2d');
                if (invoicesChartInstance) {
                    invoicesChartInstance.destroy();
                }
                invoicesChartInstance = new Chart(ctxInv, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Invoices',
                            data: invoices,
                            borderColor: '#0ea5e9',
                            backgroundColor: 'rgba(14,165,233,0.15)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 2
                        }]
                    },
                    options: commonOptions
                });
            }
        }

        document.addEventListener('DOMContentLoaded', initCharts);
        document.addEventListener('livewire:navigated', initCharts);
    </script>
</x-layouts.app>
