<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('roles', 'roles.roles')
    ->name('roles')
    ->middleware('permission:|view.roles|create.roles|edit.roles|delete.roles');

    Volt::route('staffs_management/staffs', 'staffs.staffs')
    ->name('staffs')
    ->middleware('permission:|view.staffs|create.staffs|edit.staffs|delete.staffs');

    Volt::route('schools_management/schools', 'schools.schools')
    ->name('schools')
    ->middleware('permission:|view.schools|create.schools|edit.schools|delete.schools');
});

require __DIR__.'/auth.php';