<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
@php
    $dashboardRoute = auth()->user()->hasRole('student')
        ? route('student.dashboard')
        : (auth()->user()->hasRole('super admin')
            ? route('dashboard')
            : route('admins.dashboard'));
    $dashboardCurrent = auth()->user()->hasRole('student')
        ? request()->routeIs('student.dashboard')
        : (auth()->user()->hasRole('super admin')
            ? request()->routeIs('dashboard')
            : request()->routeIs('admins.dashboard'));
@endphp
<flux:navlist.item icon="home" :href="$dashboardRoute" :current="$dashboardCurrent" wire:navigate>{{ __('Dashboard') }}
</flux:navlist.item>
                @canany(['view.roles', 'create.roles', 'edit.roles', 'delete.roles'])
                    <flux:navlist.item icon="shield-check" :href="route('roles')" :current="request()->routeIs('roles')"
                        wire:navigate>{{ __('Role') }}
                    </flux:navlist.item>
                @endcanany

                @canany(['view.staffs', 'create.staffs', 'edit.staffs', 'delete.staffs'])
                    <flux:navlist.item icon="user" :href="route('staffs')" :current="request()->routeIs('staffs')"
                        wire:navigate>{{ __('Staffs Management') }}
                    </flux:navlist.item>
                @endcanany

                @canany(['view.students', 'create.students', 'edit.students', 'delete.students'])
                    <flux:navlist.item icon="user" :href="route('students')" :current="request()->routeIs('students')"
                        wire:navigate>{{ __('Students Management') }}
                    </flux:navlist.item>
                @endcanany

                @canany(['view.schools', 'create.schools', 'edit.schools', 'delete.schools'])
                    <flux:navlist.item icon="academic-cap" :href="route('schools')"
                        :current="request()->routeIs('schools')" wire:navigate>{{ __('Schools') }}
                    </flux:navlist.item>
                @endcanany
            </flux:navlist.group>
        </flux:navlist>

        @canany(['stamp.school.fees.invoices', 'stamp.igr.invoices'])
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Student Receipts')" class="grid" expandable>
                {{-- @can(['stamp.school.fees.invoices'])
                    <flux:navlist.item icon="document-check" :href="route('stamp.schoolfees.invoices')"
                        :current="request()->routeIs('stamp.schoolfees.invoices')" wire:navigate>{{ __('School fees invoices') }}
                    </flux:navlist.item>
                @endcan --}}
                @can(['stamp.igr.invoices'])
                    <flux:navlist.item icon="document-check" :href="route('stamp.igrs.invoices')"
                        :current="request()->routeIs('stamp.igrs.invoices')" wire:navigate>{{ __('Receipts') }}
                    </flux:navlist.item>
                @endcan
            </flux:navlist.group>
        </flux:navlist>
        @endcanany

        {{-- Student Navigation Section --}}
        @role('student')
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Student Portal')" class="grid" expandable>
                <flux:navlist.item icon="home" :href="route('student.dashboard')" 
                    :current="request()->routeIs('student.dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>
                
                <flux:navlist.item icon="document-text" :href="route('invoice.upload')" 
                    :current="request()->routeIs('invoice.upload')" wire:navigate>
                    {{ __('Upload Invoices') }}
                </flux:navlist.item>
                
                <flux:navlist.item icon="document-check" :href="route('student.stamped-documents')" 
                    :current="request()->routeIs('student.stamped-documents')" wire:navigate>
                    {{ __('Stamped Documents') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>
        @endrole
        
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Admin Tools')" class="grid" expandable>
                @canany(['stamp.school.fees.invoices', 'stamp.igr.invoices'])
                    <flux:navlist.item icon="pencil" :href="route('admin.signature')"
                        :current="request()->routeIs('admin.signature')" wire:navigate>{{ __('Digital Signature') }}
                    </flux:navlist.item>
                @endcanany
                @role('super admin')
                    <flux:navlist.item icon="document-duplicate" :href="route('admin.stamps')"
                        :current="request()->routeIs('admin.stamps')" wire:navigate>{{ __('Stamp Management') }}
                    </flux:navlist.item>
                @endrole
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline" class="hidden">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist>

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>
                
                <flux:menu.separator />
                
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
                <flux:menu.separator />

                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                    <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                </flux:radio.group>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    @role('student')
        <x-topbar/>
    @else
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>
    @endrole

    {{ $slot }}

    @fluxScripts
</body>

</html>
