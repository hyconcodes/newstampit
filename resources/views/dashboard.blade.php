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
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-800 p-4">
                        <div class="flex items-center gap-3">
                            <div class="rounded-md bg-green-50 dark:bg-green-900/30 p-2 text-green-600 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5"><path d="M4.5 6.75A2.25 2.25 0 016.75 4.5h.75a.75.75 0 01.75.75v2.25h5.25V5.25a.75.75 0 01.75-.75h.75a2.25 2.25 0 012.25 2.25V9a.75.75 0 01-.75.75h-12A.75.75 0 014.5 9V6.75z"/><path fill-rule="evenodd" d="M3 9.75A.75.75 0 013.75 9h16.5a.75.75 0 01.75.75v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15.75v-6zM7.5 12a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">School Fees Admins</p>
                                <p class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ \Spatie\Permission\Models\Role::findByName('school fees admin')->users()->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-800 p-4">
                        <div class="flex items-center gap-3">
                            <div class="rounded-md bg-sky-50 dark:bg-sky-900/30 p-2 text-sky-600 dark:text-sky-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5"><path d="M6.75 3A2.25 2.25 0 004.5 5.25v13.5A2.25 2.25 0 006.75 21h10.5A2.25 2.25 0 0019.5 18.75V8.56a2.25 2.25 0 00-.659-1.591l-3.81-3.81A2.25 2.25 0 0013.44 2.5H6.75z"/><path d="M12 12a.75.75 0 000 1.5h3.75a.75.75 0 000-1.5H12zm-3.75 3a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5h-7.5z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">IGRS Admins</p>
                                <p class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ \Spatie\Permission\Models\Role::findByName('igrs admin')->users()->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-800 p-4">
                        <div class="flex items-center gap-3">
                            <div class="rounded-md bg-emerald-50 dark:bg-emerald-900/30 p-2 text-emerald-600 dark:text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5"><path d="M16.5 7.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/><path d="M3.75 20.25A8.25 8.25 0 0112 12a8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">Students</p>
                                <p class="mt-1 text-2xl font-semibold text-neutral-900 dark:text-neutral-100">{{ \Spatie\Permission\Models\Role::findByName('student')->users()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
