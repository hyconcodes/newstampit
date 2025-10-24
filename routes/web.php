<?php

use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'role:super admin'])
    ->name('dashboard');

Volt::route('student/dashboard', 'students.dashboard')
    ->middleware(['auth', 'verified.otp', 'role:student'])
    ->name('student.dashboard');

Volt::route('admin/dashboard', 'admins.dashboard')
    ->middleware(['auth', 'verified', 'role:school fees admin|igrs admin'])
    ->name('admins.dashboard');

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
    //
    Volt::route('invoice/pending/invoices', 'students.pending-invoices')
        ->name('student.pending.invoices')
        ->middleware('permission:|upload.invoices');

    Volt::route('invoice/update-pending/{id}/invoices', 'students.update-pending-invoices')
        ->name('student.update-pending.invoices')
        ->middleware('permission:|upload.invoices');

    // for student to view stamped invoices
    Volt::route('invoice/stamped/documents', 'invoice.stamped-documents')
        ->name('student.stamped-documents')
        ->middleware('permission:|upload.invoices');

    //route for officers to stamp school fees inv
    Volt::route('admin/school-fees/invoices', 'admins.schoolfees-documents')
        ->name('stamp.schoolfees.invoices')
        ->middleware('permission:|stamp.school.fees.invoices');

    //route for officers to stamp igrs inv
    Volt::route('admin/igrs/invoices', 'admins.igrs-documents')
        ->name('stamp.igrs.invoices')
        ->middleware('permission:|stamp.igr.invoices');

    //route for admins to manage their signature
    Volt::route('admin/signature', 'admins.signature')
        ->name('admin.signature')
        ->middleware('permission:|stamp.school.fees.invoices|stamp.igr.invoices');

    //route for admins to manage stamps
    Volt::route('admin/stamps', 'admins.stamps')
        ->name('admin.stamps')
        ->middleware(['auth', 'verified', 'role:super admin']);

    // Route for downloading stamped invoices
    Route::get('/download/stamped-invoice/{invoice}', function ($invoice) {
        $invoice = \App\Models\Invoice::findOrFail($invoice);

        // Check if stamped file exists
        if (!$invoice->stamped_file) {
            abort(404, 'Stamped invoice not found.');
        }

        $filePath = storage_path('app/public/' . $invoice->stamped_file);

        if (!file_exists($filePath)) {
            abort(404, 'Stamped invoice file not found.');
        }

        // Generate descriptive filename
        $feeType = ucfirst(str_replace('_', ' ', $invoice->fee_type));
        $fileName = "Stamped_{$feeType}_Receipt_{$invoice->rrr}.pdf";

        return response()->download($filePath, $fileName);
    })->name('download.stamped.invoice');

    // dummy/testing route
    // Route::get('invoice/download')->name('invoices.edit');
    Route::get('/migrate', function () {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migration executed successfully.';
    })->name('migrate');

    Route::get('/storage-link', function () {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return 'Storage link created successfully.';
    })->name('storage.link');
});



// Guest
Route::get('/verify-otp/{user}', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/verify-otp/{user}', [OtpController::class, 'verify'])->name('otp.check');
Route::post('/resend-otp/{user}', [OtpController::class, 'resend'])->name('otp.resend');

// Password reset OTP routes
Route::post('/password-reset-otp', [OtpController::class, 'sendPasswordResetOtp'])->name('password.reset.otp.send');
Route::get('/password-reset-otp/{user}', [OtpController::class, 'showPasswordResetOtpVerifyForm'])->name('password.reset.otp.verify');
Route::post('/password-reset-otp/{user}', [OtpController::class, 'verifyPasswordResetOtp'])->name('password.reset.otp.check');
Route::post('/password-reset-otp-resend/{user}', [OtpController::class, 'resend'])->name('password.reset.otp.resend');

Route::get('/test-otp-mail', function () {
    return new \App\Mail\OtpMail(445588);
});

Route::get('/test-invoice-stamped-mail/{invoice}', function (\App\Models\Invoice $invoice) {
    return new \App\Mail\InvoiceStampedMail($invoice);
});


require __DIR__ . '/auth.php';
