<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public $student;

    public function mount($id)
    {
        $this->student = User::find($id);

        if (!$this->student) {
            session()->flash('error', 'Student not found');
            abort(404);
        }
    }

    public function title(): string
    {
        if (!$this->student) {
            return 'Student Not Found';
        }

        return $this->student->name . ' - Student Profile';
    }
};?>

<div class="max-w-4xl mx-auto p-4 sm:p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-lg">
    <x-slot name="title">{{ $student->name }} - Student Profile</x-slot>

    <div class="flex flex-row items-center justify-between border-b pb-4 mb-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-800 dark:text-white mb-2 sm:mb-0">Student Profile</h1>
        <a href="{{ url()->previous() }}" class="btn btn-ghost btn-sm">
            <i class="ri-arrow-left-line">Back</i>
        </a>
    </div>

    <div class="flex flex-col sm:flex-row items-start justify-between mb-4">
        <div class="flex items-center space-x-3 mb-2 sm:mb-0">
            <img src="{{ $student->picture ? asset('storage/app/public/' . $student->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) }}"
                alt="{{ $student->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-green-200" />
            <div>
                <h3 class="font-semibold text-foreground dark:text-white">{{ $student->name }}</h3>
                <p class="text-sm text-muted-foreground dark:text-zinc-400">Matric Number: {{ $student->matric_no }}</p>
            </div>
        </div>
        <button class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="1" />
                <circle cx="19" cy="12" r="1" />
                <circle cx="5" cy="12" r="1" />
            </svg>
        </button>
    </div>

    <div class="space-y-3">
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground dark:text-zinc-400">School:</span>
            <span class="font-medium text-foreground dark:text-white">{{ $student->school->name ?? "pending..." }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground dark:text-zinc-400">Joined:</span>
            <span
                class="font-medium text-foreground dark:text-white">{{ $student->created_at->format('M d, Y') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground dark:text-zinc-400">Total Amount:</span>
            <span class="font-bold text-primary">{{ $student->totalAmount ?? '80,000' }}</span>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-border dark:border-zinc-700">
        <div class="grid grid-cols-3 sm:grid-cols-3 gap-4 text-center">
            <div class="p-2">
                <div class="text-lg font-bold text-foreground dark:text-white">{{ $student->invoicesSubmitted ?? '10' }}
                </div>
                <div class="text-xs text-muted-foreground dark:text-zinc-400">Submitted</div>
            </div>
            <div class="p-2">
                <div class="text-lg font-bold text-success">{{ $student->invoicesApproved ?? '10' }}</div>
                <div class="text-xs text-muted-foreground dark:text-zinc-400">Approved</div>
            </div>
            <div class="p-2">
                <div class="text-lg font-bold text-warning">{{ $student->invoicesPending ?? '10' }}</div>
                <div class="text-xs text-muted-foreground dark:text-zinc-400">Pending</div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
        <div class="flex items-center text-xs text-muted-foreground dark:text-zinc-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Last active:
            {{ $student->lastActivity ? $student->lastActivity->diffForHumans() : now()->diffForHumans() }}
        </div>
        <flux:button class="btn btn-outline btn-xs w-full sm:w-auto view-details-btn cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" />
            </svg>
            Chat
        </flux:button>
    </div>
</div>
