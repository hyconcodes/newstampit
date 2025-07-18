<?php

use App\Models\User;
use App\Models\School;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $school_id = '';

    public function schools()
    {
        return School::all();
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'exists:schools,id'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:' . User::class,
                'regex:/^[a-zA-Z0-9.]+\.[0-9]+@bouesti\.edu\.ng$/',
                function($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@bouesti.edu.ng')) {
                        $fail('Please use your school email address (@bouesti.edu.ng).');
                    }
                    
                    $parts = explode('.', explode('@', $value)[0]);
                    if (count($parts) < 2) {
                        $fail('Invalid email format.');
                        return;
                    }
                    
                    $matric_number = $parts[1];
                    
                    $exists = User::where('email', 'LIKE', '%.' . $matric_number . '@bouesti.edu.ng')->exists();
                    if ($exists) {
                        $fail('A student with this matric number already exists.');
                    }
                }
            ],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Extract matric number from email
        $parts = explode('.', explode('@', $validated['email'])[0]);
        $validated['matric_no'] = $parts[1];

        event(new Registered(($user = User::create($validated))));

        $user->assignRole('student');

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    @include('includes.alert')
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- School -->
        <flux:select
            wire:model="school_id"
            :label="__('School')"
            required
        >
            <option value="">Select your school</option>
            @foreach($this->schools() as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
            @endforeach
        </flux:select>

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
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
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate class="text-green-600 hover:text-green-700">{{ __('Log in') }}</flux:link>
    </div>
</div>
