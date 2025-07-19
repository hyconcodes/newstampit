<?php

use Livewire\Volt\Component;
use App\Models\User;

new class extends Component {
    public $student;

    public function mount($id)
    {
        $this->student = User::find($id);
    }
}; ?>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="border-b pb-4 mb-4">
        <h1 class="text-3xl font-bold text-gray-800">Student Profile</h1>
    </div>

    @if($student)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-sm font-semibold text-gray-600">Full Name</h2>
                    <p class="text-lg text-gray-800">{{ $student->name }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-sm font-semibold text-gray-600">Email Address</h2>
                    <p class="text-lg text-gray-800">{{ $student->email }}</p>
                </div>
                @if($student->phone)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-sm font-semibold text-gray-600">Phone Number</h2>
                        <p class="text-lg text-gray-800">{{ $student->phone }}</p>
                    </div>
                @endif
            </div>
            <div class="space-y-4">
                @if($student->created_at)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-sm font-semibold text-gray-600">Joined Date</h2>
                        <p class="text-lg text-gray-800">{{ $student->created_at->format('F d, Y') }}</p>
                    </div>
                @endif
                @if($student->address)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h2 class="text-sm font-semibold text-gray-600">Address</h2>
                        <p class="text-lg text-gray-800">{{ $student->address }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <div class="text-gray-400 text-xl">
                <i class="fas fa-user-slash text-4xl mb-4"></i>
                <p>Student information not found</p>
            </div>
        </div>
    @endif
</div>
