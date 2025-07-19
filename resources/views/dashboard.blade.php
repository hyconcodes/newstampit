<x-layouts.app :title="__('Dashboard')">
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
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
