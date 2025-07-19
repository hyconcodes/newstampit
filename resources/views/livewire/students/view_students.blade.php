<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public $student;

    public function mount($id)
    {
        $this->student = User::find($id);

        if (!$this->student) {
            seesion()->flash('error', 'Student not found');
            return redirect()->back();
        }
    }
}; ?>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="border-b pb-4 mb-4">
        <h1 class="text-3xl font-bold text-gray-800">Student Profile</h1>
    </div>

    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('storage/' . $student->picture) }}" alt="{{ $student->name }}"
                class="w-12 h-12 rounded-full object-cover ring-2 ring-blue-200" />
            <div>
                <h3 class="font-semibold text-foreground">{{ $student->name }}</h3>
                <p class="text-sm text-muted-foreground">{{ $student->matric_no }}</p>
            </div>
        </div>
        <button class="btn btn-ghost btn-sm">
            <i data-lucide="more-horizontal" class="w-4 h-4"></i>
        </button>
    </div>

    <div class="space-y-3">
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">School:</span>
            <span class="font-medium text-foreground">{{ $student->school->name }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">Joined:</span>
            <span class="font-medium text-foreground">{{ $student->created_at }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">Total Amount:</span>
            <span class="font-bold text-primary">{{ $student->totalAmount ?? '80,000' }}</span>
        </div>
    </div>

    <div class="mt-4 pt-4 border-t border-border">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-lg font-bold text-foreground">{{ $student->invoicesSubmitted ?? "pending..." }}</div>
                <div class="text-xs text-muted-foreground">Submitted</div>
            </div>
            <div>
                <div class="text-lg font-bold text-success">{{ $student->invoicesApproved ?? "pending..." }}</div>
                <div class="text-xs text-muted-foreground">Approved</div>
            </div>
            <div>
                <div class="text-lg font-bold text-warning">{{ $student->invoicesPending ?? "pending..." }}</div>
                <div class="text-xs text-muted-foreground">Pending</div>
            </div>
        </div>
    </div>

    <div class="mt-4 flex justify-between items-center">
        <div class="flex items-center text-xs text-muted-foreground">
            <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
            Last active: {{ $student->lastActivity ?? now() }}
        </div>
        <button class="btn btn-outline btn-xs view-details-btn">
            <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
            View Details
        </button>
    </div>
</div>
