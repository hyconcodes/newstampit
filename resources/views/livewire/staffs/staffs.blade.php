<?php

use Livewire\Volt\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $search = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRoles = [];
    public $editingStaffId = null;
    public $showDeleteModal = false;
    public $staffToDelete = null;
    
    public function with(): array
    {
        return [
            'staffs' => User::whereHas('roles', function($query){
                $query->whereNotIn('name', ['student', 'super admin']);
            })->where(function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                })
                ->with('roles')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'roles' => Role::whereNotIn('name', ['student', 'super admin'])->get()
        ];
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => [
                'required',
                'email',
                'unique:users,email'.($this->editingStaffId ? ','.$this->editingStaffId : ''),
                function($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z]+\.[a-zA-Z]+@bouesti\.edu\.ng$/', $value)) {
                        $fail('Please use your official university email address (e.g., firstname.lastname@bouesti.edu.ng)');
                    }
                }
            ],
            'password' => $this->editingStaffId ? 'nullable|min:6' : 'required|min:6',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id'
        ]);

        if($this->editingStaffId) {
            $staff = User::find($this->editingStaffId);
            $staff->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            
            $roles = Role::whereIn('id', $this->selectedRoles)->get();
            $roleNames = $roles->pluck('name')->toArray();
            $staff->syncRoles($roleNames);
            
            if($this->password) {
                $staff->update(['password' => Hash::make($this->password)]);
            }

            session()->flash('success', 'Staff member updated successfully.');
        } else {
            $staff = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            
            $roles = Role::whereIn('id', $this->selectedRoles)->get();
            $roleNames = $roles->pluck('name')->toArray();
            $staff->syncRoles($roleNames);

            $staff->sendEmailVerificationNotification();

            session()->flash('success', 'Staff member created successfully.');
        }

        $this->reset(['name', 'email', 'password', 'selectedRoles', 'editingStaffId']);
    }

    public function edit($staffId)
    {
        $staff = User::find($staffId);
        $this->editingStaffId = $staff->id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->selectedRoles = $staff->roles->pluck('id')->toArray();
    }

    public function confirmDelete($staffId)
    {
        $this->staffToDelete = $staffId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $staff = User::find($this->staffToDelete);
        $staff->roles()->detach();
        $staff->delete();
        
        $this->showDeleteModal = false;
        $this->staffToDelete = null;
        session()->flash('success', 'Staff member deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->staffToDelete = null;
    }
}; ?>

<div class="p-2 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
        <h2 class="text-xl sm:text-2xl font-semibold">Staff Management</h2>
        <div class="w-full sm:w-auto">
            <flux:input type="text" wire:model.live.debounce.300ms="search" placeholder="Search staff..." 
                class="w-full sm:w-auto rounded border-gray-300 shadow-sm"/>
        </div>
    </div>
@include('includes.alert')
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
        <h3 class="text-lg font-medium mb-4">{{ $editingStaffId ? 'Edit Staff' : 'Add New Staff' }}</h3>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <div class="flex items-center gap-2">
                    <flux:avatar name="{{ $name }}" color="auto" color:seed="{{ $editingStaffId ?? 'new' }}" />
                    <flux:input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"/>
                </div>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <flux:input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"/>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password {{ $editingStaffId ? '(Leave blank to keep current)' : '' }}</label>
                <flux:input type="password" wire:model="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"/>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Roles</label>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($roles as $role)
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('selectedRoles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <flux:button type="submit" class="w-full sm:w-auto !bg-green-500 text-white px-4 py-2 rounded !hover:bg-green-600">
                {{ $editingStaffId ? 'Update Staff' : 'Add Staff' }}
            </flux:button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($staffs as $staff)
                                <tr>
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <flux:avatar name="{{ $staff->name }}" color="auto" color:seed="{{ $staff->id }}" />
                                            <div class="text-sm">{{ $staff->name }}</div>
                                        </div>
                                        <div class="sm:hidden text-xs text-gray-500 mt-1">{{ $staff->email }}</div>
                                        <div class="sm:hidden flex flex-wrap gap-1 mt-1">
                                            @foreach($staff->roles as $role)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">{{ $staff->email }}</td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                                        @foreach($staff->roles as $role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <flux:button wire:click="edit({{ $staff->id }})" 
                                                class="!text-green-600 !hover:text-green-900">Edit</flux:button>
                                            <flux:button wire:click="confirmDelete({{ $staff->id }})" 
                                                class="!text-red-600 !hover:text-red-900">Delete</flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="px-3 sm:px-6 py-4">
            {{ $staffs->links() }}
        </div>
    </div>

    @if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Delete Staff Member</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete this staff member? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <flux:button wire:click="delete" class="!bg-red-600 !text-white sm:ml-3 sm:w-auto !hover:bg-red-700">
                            Delete
                        </flux:button>
                        <flux:button wire:click="cancelDelete" class="mt-3 sm:mt-0 !bg-white !text-gray-900 !hover:bg-gray-50">
                            Cancel
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
