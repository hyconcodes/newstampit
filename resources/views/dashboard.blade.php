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
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
@php
    $roles = [
        'school fees admin' => [
            'count' => \Spatie\Permission\Models\Role::findByName('school fees admin')->users()->count(),
            'color' => '#16a34a',
            'bg'    => 'rgba(22,163,74,0.15)'
        ],
        'igrs admin' => [
            'count' => \Spatie\Permission\Models\Role::findByName('igrs admin')->users()->count(),
            'color' => '#0ea5e9',
            'bg'    => 'rgba(14,165,233,0.15)'
        ],
        'student' => [
            'count' => \Spatie\Permission\Models\Role::findByName('student')->users()->count(),
            'color' => '#10b981',
            'bg'    => 'rgba(16,185,129,0.15)'
        ]
    ];
    $total = collect($roles)->sum('count');
@endphp
<div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-5">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">System Administrators</h3>
            <p class="mt-1 text-xl font-semibold text-neutral-900 dark:text-neutral-100">Overview</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-lg bg-green-100 dark:bg-green-900/30 px-3 py-1 text-xs text-green-700 dark:text-green-400">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4"><path fill-rule="evenodd" d="M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5zM16.28 9.97a.75.75 0 10-1.06-1.06l-3.72 3.72-1.72-1.72a.75.75 0 10-1.06 1.06l2.25 2.25c.3.3.79.3 1.06 0l4.25-4.25z" clip-rule="evenodd"/></svg>
            <span>Active</span>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex flex-col gap-3">
            @foreach($roles as $label => $role)
                <div class="flex items-center justify-between rounded-lg border border-neutral-200 dark:border-neutral-800 p-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex size-3 rounded-full" style="background-color:{{ $role['color'] }}"></span>
                        <span class="text-sm text-neutral-600 dark:text-neutral-300 capitalize">{{ str_replace(['admin','fees'],'',$label) }}</span>
                    </div>
                    <span class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $role['count'] }}</span>
                </div>
            @endforeach
            <div class="flex items-center justify-between rounded-lg bg-neutral-100 dark:bg-neutral-800 p-3">
                <span class="text-sm text-neutral-600 dark:text-neutral-300">Total</span>
                <span class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">{{ $total }}</span>
            </div>
        </div>

        <div class="relative aspect-square w-full max-w-xs mx-auto">
            <canvas id="rolesPieChart"></canvas>
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
                            const label = ctx.label.replace(/admin|fees/gi,'').trim().toUpperCase();
                            return `${label}: ${ctx.parsed}`;
                        }
                    }
                }
            }
        }
    });
});
</script>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
                <div class="font-semibold text-gray-900 dark:text-white mb-2">Student Registrations (30 days)</div>
                <canvas id="registrationsChart"></canvas>
            </div>
        </div>
        <div
            class="relative h-70 aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
            <div class="font-semibold text-gray-900 dark:text-white mb-2">Invoice Uploads (30 days)</div>
            <canvas id="invoicesChart"></canvas>
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
