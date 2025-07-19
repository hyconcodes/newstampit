<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\School;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $search = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $school_id = '';
    public $matric_no = '';
    public $editingStudentId = null;
    public $showDeleteModal = false;
    public $studentToDelete = null;
    
    public function with(): array
    {
        return [
            'students' => User::role('student')
                ->where(function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('matric_no', 'like', '%'.$this->search.'%');
                })
                ->with(['school'])
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'schools' => School::all()
        ];
    }

    public function extractMatricNo($email)
    {
        if (preg_match('/\.(\d+)@bouesti\.edu\.ng$/', $email, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => [
                'required',
                'email',
                'unique:users,email'.($this->editingStudentId ? ','.$this->editingStudentId : ''),
                function($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z]+\.\d+@bouesti\.edu\.ng$/', $value)) {
                        $fail('Please use the official university email address (e.g., firstname.{matric number}@bouesti.edu.ng)');
                    }
                    
                    $matric_no = $this->extractMatricNo($value);
                    if ($matric_no) {
                        $existingUser = User::where('matric_no', $matric_no)
                            ->when($this->editingStudentId, function($query) {
                                return $query->where('id', '!=', $this->editingStudentId);
                            })
                            ->first();
                            
                        if ($existingUser) {
                            $fail('A student with this matric number already exists.');
                        }
                    }
                }
            ],
            'password' => $this->editingStudentId ? 'nullable|min:6' : 'required|min:6',
            'school_id' => 'required|exists:schools,id'
        ]);

        $matric_no = $this->extractMatricNo($this->email);

        if($this->editingStudentId) {
            $student = User::find($this->editingStudentId);
            $student->update([
                'name' => $this->name,
                'email' => $this->email,
                'school_id' => $this->school_id,
                'matric_no' => $matric_no,
            ]);
            
            if($this->password) {
                $student->update(['password' => Hash::make($this->password)]);
            }

            session()->flash('success', 'Student updated successfully.');
        } else {
            $student = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'school_id' => $this->school_id,
                'matric_no' => $matric_no,
            ]);
            
            $student->assignRole('student');
            $student->sendEmailVerificationNotification();

            session()->flash('success', 'Student created successfully.');
        }

        $this->reset(['name', 'email', 'password', 'school_id', 'editingStudentId']);
    }

    public function edit($studentId)
    {
        $student = User::find($studentId);
        $this->editingStudentId = $student->id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->school_id = $student->school_id;
    }

    public function confirmDelete($studentId)
    {
        $this->studentToDelete = $studentId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $student = User::find($this->studentToDelete);
        $student->roles()->detach();
        $student->delete();
        
        $this->showDeleteModal = false;
        $this->studentToDelete = null;
        session()->flash('success', 'Student deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->studentToDelete = null;
    }
}; ?>

<div class="p-2 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
        <h2 class="text-xl sm:text-2xl font-semibold dark:text-white">Student Management</h2>
        <div class="w-full sm:w-auto">
            <flux:input type="text" wire:model.live.debounce.300ms="search" placeholder="Search students..." 
                class="w-full sm:w-auto rounded border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"/>
        </div>
    </div>
@include('includes.alert')
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4 sm:p-6 mb-6">
        <h3 class="text-lg font-medium mb-4 dark:text-white">{{ $editingStudentId ? 'Edit Student' : 'Add New Student' }}</h3>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Name</label>
                <div class="flex items-center gap-2">
                    <flux:avatar name="{{ $name }}" color="auto" color:seed="{{ $editingStudentId ?? 'new' }}" />
                    <flux:input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"/>
                </div>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                <flux:input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"/>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">School</label>
                <flux:select wire:model="school_id" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Select School</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name ?? "Not assign" }}</option>
                    @endforeach
                </flux:select>
                @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Password {{ $editingStudentId ? '(Leave blank to keep current)' : '' }}</label>
                <flux:input type="password" wire:model="password" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm"/>
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <flux:button type="submit" class="w-full sm:w-auto !bg-green-500 text-white px-4 py-2 rounded !hover:bg-green-600">
                {{ $editingStudentId ? 'Update Student' : 'Add Student' }}
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
                                <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Email</th>
                                <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Matric No</th>
                                <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">School</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach($students as $student)
                                <tr onclick="window.location.href = '{{ route('student.show', $student->id) }}'" class="cursor-pointer">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <flux:avatar name="{{ $student->name }}" color="auto" color:seed="{{ $student->id }}" />
                                            <div class="text-sm dark:text-white">{{ $student->name }}</div>
                                        </div>
                                        <div class="sm:hidden text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $student->email }}</div>
                                        <div class="sm:hidden text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $student->matric_no ?? "pending..." }}</div>
                                        <div class="sm:hidden text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $student->school->name ?? "Not assign" }}</div>
                                    </td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap dark:text-white">{{ $student->email }}</td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap dark:text-white">{{ $student->matric_no ?? "pending..." }}</td>
                                    <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap dark:text-white">{{ $student->school->name ?? "Not assign" }}</td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <flux:button wire:click="edit({{ $student->id }})" 
                                                class="!text-green-600 dark:!text-green-400 !hover:text-green-900 dark:!hover:text-green-300">Edit</flux:button>
                                            <flux:button wire:click="confirmDelete({{ $student->id }})" 
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
            {{ $students->links() }}
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
                                <h3 class="text-base font-semibold leading-6 text-zinc-900 dark:text-white">Delete Student</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Are you sure you want to delete this student? This action cannot be undone.</p>
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
