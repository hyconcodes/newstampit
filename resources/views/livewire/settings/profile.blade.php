<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $school = '';
    public string $matric_no = '';
    public $photo;
    public $temp_photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->school = Auth::user()->school->name ?? 'waiting...';
        $this->matric_no = Auth::user()->matric_no ?? 'waiting...';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:1024'], // 1MB Max
            /*'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],*/
        ]);

        if ($this->photo) {
            $validated['picture'] = $this->photo->storeAs('picture', time() . '.' . $this->photo->getClientOriginalExtension(), 'public');
        }

        $user->fill($validated);

        /*if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }*/

        $user->save();

        $this->reset('photo', 'temp_photo');
        session()->flash('success', 'Saved');
        $this->dispatch('profile-updated', name: $user->name, photo: $user->picture);
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => ['image', 'max:1024']
        ]);
        
        $this->temp_photo = $this->photo->temporaryUrl();
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="col-span-6 sm:col-span-4 flex flex-col items-center">
                @if($temp_photo)
                    <img src="{{ $temp_photo }}" class="rounded-full h-20 w-20 object-cover mb-4">
                @elseif(auth()->user()->picture)
                    <img src="{{ asset('storage/app/public/' . auth()->user()->picture) }}" class="rounded-full h-20 w-20 object-cover mb-4">
                    {{-- <img src="{{ Storage::url(auth()->user()->picture) }}" class="rounded-full h-20 w-20 object-cover"> --}}
                @endif
                <flux:input type="file" wire:model="photo" :label="__('Profile Photo')" accept="image/*" class="w-full" />
                @error('photo') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" disabled />
                {{-- <flux:text class="mt-1 text-sm text-gray-500">Email cannot be updated</flux:text> --}}
            </div>

            <div>
                <flux:input wire:model="school" :label="__('School')" type="text" disabled />
                {{-- <flux:text class="mt-1 text-sm text-gray-500">School cannot be updated</flux:text> --}}
            </div>

            <div>
                <flux:input wire:model="matric_no" :label="__('Matric Number')" type="text" disabled />
                {{-- <flux:text class="mt-1 text-sm text-gray-500">Matric number cannot be updated</flux:text> --}}
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                <div>
                    <flux:text class="mt-4">
                        {{ __('Your email address is unverified.') }}

                        <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </flux:link>
                    </flux:text>

                    @if (session('status') === 'verification-link-sent')
                        <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </flux:text>
                    @endif
                </div>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
