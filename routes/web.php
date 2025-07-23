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

    Volt::route('students_management/students', 'students.students')
    ->name('students')
    ->middleware('permission:|view.students|create.students|edit.students|delete.students');

    Volt::route('view/students/{id}/students', 'students.view_students')
    ->name('student.show')
    ->middleware('permission:|view.students');

    Volt::route('schools_management/schools', 'schools.schools')
    ->name('schools')
    ->middleware('permission:|view.schools|create.schools|edit.schools|delete.schools');

    Volt::route('view/{id}/schools', 'schools.view_schools')
    ->name('schools.show')
    ->middleware('permission:|view.schools|create.schools|edit.schools|delete.schools');

    Volt::route('invoice-upload', 'invoice.invoice-upload')
    ->name('invoice.upload')
    ->middleware('permission:|upload.invoices');
});

require __DIR__.'/auth.php';