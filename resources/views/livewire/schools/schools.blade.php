<?php

use Livewire\Volt\Component;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $search = '';
    public $name = '';
    public $editingSchoolId = null;
    public $showDeleteModal = false;
    public $schoolToDelete = null;
    
    public function with(): array
    {
        return [
            'schools' => School::where('name', 'like', '%'.$this->search.'%')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ];
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|unique:schools,name'.($this->editingSchoolId ? ','.$this->editingSchoolId : ''),
        ]);

        if($this->editingSchoolId) {
            $school = School::find($this->editingSchoolId);
            $school->update([
                'name' => $this->name,
            ]);

            session()->flash('success', 'School updated successfully.');
        } else {
            School::create([
                'name' => $this->name,
            ]);

            session()->flash('success', 'School created successfully.');
        }

        $this->reset(['name', 'editingSchoolId']);
    }

    public function edit($schoolId)
    {
        $school = School::find($schoolId);
        $this->editingSchoolId = $school->id;
        $this->name = $school->name;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'editingSchoolId']);
    }

    public function confirmDelete($schoolId)
    {
        $this->schoolToDelete = $schoolId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $school = School::find($this->schoolToDelete);
        $school->delete();
        
        $this->showDeleteModal = false;
        $this->schoolToDelete = null;
        session()->flash('success', 'School deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->schoolToDelete = null;
    }
}; ?>

<div class="p-2 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
        <h2 class="text-xl sm:text-2xl font-semibold dark:text-white">School Management</h2>
        <div class="w-full sm:w-auto">
            <flux:input type="text" wire:model.live.debounce.300ms="search" placeholder="Search school..." 
                class="w-full sm:w-auto rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"/>
        </div>
    </div>
{{-- @include('includes.alert') --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4 sm:p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium dark:text-white">{{ $editingSchoolId ? 'Edit School' : 'Add New School' }}</h3>
            @if($editingSchoolId)
            <flux:button wire:click="cancelEdit" class="!text-zinc-600 dark:!text-zinc-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </flux:button>
            @endif
        </div>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name</label>
                <flux:input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"/>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <flux:button type="submit" class="w-full sm:w-auto !bg-green-500 text-white px-4 py-2 rounded !hover:bg-green-600">
                {{ $editingSchoolId ? 'Update School' : 'Add School' }}
            </flux:button>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-700">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Name</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($schools as $school)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700 cursor-pointer" onclick="window.location.href='{{ route('schools.show', $school->id) }}'">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm dark:text-white">{{ $school->name }}</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <flux:button wire:click="edit({{ $school->id }})" 
                                                class="!text-green-600 dark:!text-green-400 !hover:text-green-900 dark:!hover:text-green-300">Edit</flux:button>
                                            <flux:button wire:click="confirmDelete({{ $school->id }})" 
                                                class="!text-red-600 dark:!text-red-400 !hover:text-red-900 dark:!hover:text-red-300">Delete</flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="px-3 sm:px-6 py-4 dark:bg-zinc-800">
            {{ $schools->links() }}
        </div>
    </div>

    @if($showDeleteModal)
    <div class="fixed inset-0 bg-zinc-500 dark:bg-zinc-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity z-50">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-zinc-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-zinc-900 dark:text-white">Delete School</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Are you sure you want to delete this school? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <flux:button wire:click="delete" class="!bg-red-600 !text-white sm:ml-3 sm:w-auto !hover:bg-red-700">
                            Delete
                        </flux:button>
                        <flux:button wire:click="cancelDelete" class="mt-3 sm:mt-0 !bg-white dark:!bg-zinc-600 !text-zinc-900 dark:!text-white !hover:bg-zinc-50 dark:!hover:bg-zinc-500">
                            Cancel
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
