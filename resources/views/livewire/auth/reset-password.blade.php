<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        // Check if user has valid OTP verification session
        if (!session('password_reset_user_id')) {
            $this->redirectRoute('password.request', navigate: true);
        }
    }

    /**
     * Reset the password for the given user using OTP verification.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get user ID from session (set during OTP verification)
        $userId = session('password_reset_user_id');
        
        if (!$userId) {
            $this->addError('password', 'Invalid session. Please request a new password reset.');
            return;
        }

        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            $this->addError('password', 'User not found. Please request a new password reset.');
            return;
        }

        // Update the password
        $user->forceFill([
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Clear the session
        session()->forget('password_reset_user_id');

        event(new PasswordReset($user));

        Session::flash('status', 'Your password has been reset successfully.');

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="resetPassword" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full bg-green-600 hover:bg-green-700">
                {{ __('Reset password') }}
            </flux:button>
        </div>
    </form>
</div>
