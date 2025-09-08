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
            $day = now()->subDays(29 - $i)->toDateString();
            $labels[] = $day;
            $registrationSeries[] = isset($registrations[$day]) ? (int)$registrations[$day]->count : 0;
            $invoiceSeries[] = isset($invoices[$day]) ? (int)$invoices[$day]->count : 0;
        }
    @endphp
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="absolute inset-0 flex flex-col justify-center items-center p-4 bg-white dark:bg-neutral-900">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">System Administrators</div>
                    <div class="mt-2 flex gap-4 text-sm">
                        <div class="text-center">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ \Spatie\Permission\Models\Role::findByName('school fees admin')->users()->count() }}</div>
                            <div class="text-gray-500 dark:text-neutral-400">School Fees Admins</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ \Spatie\Permission\Models\Role::findByName('igrs admin')->users()->count() }}</div>
                            <div class="text-gray-500 dark:text-neutral-400">IGRS Admins</div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
                <div class="font-semibold text-gray-900 dark:text-white mb-2">Student Registrations (30 days)</div>
                <canvas id="registrationsChart"></canvas>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
                <div class="font-semibold text-gray-900 dark:text-white mb-2">Invoice Uploads (30 days)</div>
                <canvas id="invoicesChart"></canvas>
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
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
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { ticks: { autoSkip: true, maxTicksLimit: 6 } },
                y: { beginAtZero: true, precision: 0 }
            }
        };

        new Chart(document.getElementById('registrationsChart').getContext('2d'), {
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

        new Chart(document.getElementById('invoicesChart').getContext('2d'), {
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
    </script>
</x-layouts.app>
