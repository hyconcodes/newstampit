<?php
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
new class extends Component {
    use WithPagination;
    public $name = '';
    public $permissions = [];
    public $selectedPermissions = [];
    public $search = '';
    public $roleId = null;
    public $showDeleteModal = false;
    public $showCreateModal = false;
    public function mount()
    {
        $this->permissions = Permission::all();
    }
    public function rules()
    {
        return [
            'name' => 'required|min:3|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'required|array|min:1',
        ];
    }
    public function create()
    {
        $this->reset(['name', 'selectedPermissions', 'roleId']);
        $this->showCreateModal = true;
    }

    public function store()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->showCreateModal = false;
        session()->flash('success', 'Role created successfully.');
        // return to_route('roles');
    }

    public function edit(Role $role)
    {
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->showCreateModal = true;
    }

    public function update()
    {
        $this->validate();

        $role = Role::find($this->roleId);
        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->showCreateModal = false;
        session()->flash('success', 'Role updated successfully.');
    }

    public function confirmDelete($id)
    {
        $this->roleId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Role::find($this->roleId)->delete();
        $this->showDeleteModal = false;
        session()->flash('success', 'Role deleted successfully.');
    }

    public function with()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')->paginate(10);

        return [
            'roles' => $roles,
        ];
    }
}; ?>

<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Role Management') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Create and manage user roles and permissions') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    @include('includes.alert')
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1">
                <flux:input wire:model.live="search" type="search" placeholder="Search roles..."
                    class="w-full px-4 py-2 border rounded-lg" />
            </div>
            <flux:button wire:click="create"
                class="ml-4 px-4 py-2 !bg-green-500 text-white rounded-lg hover:!bg-green-600">
                Create Role
            </flux:button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($roles as $role)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $role->name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($role->permissions as $permission)
                                        <span class="px-2 py-1 text-xs !bg-green-100 rounded-full">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:button wire:click="edit({{ $role->id }})"
                                    class="!text-green-500 hover:!text-green-700">
                                    Edit
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $role->id }})"
                                    class="ml-2 !text-red-500 hover:!text-red-700">
                                    Delete
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="$wire.showCreateModal"
        class="fixed inset-0 !bg-gray-500 !bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-medium mb-4">{{ $roleId ? 'Edit Role' : 'Create Role' }}</h3>
            <form wire:submit="{{ $roleId ? 'update' : 'store' }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium !text-gray-700 mb-1">Role Name</label>
                    <flux:input wire:model="name" type="text" class="w-full px-4 py-2 border rounded-lg" />
                    @error('name')
                        <span class="!text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium !text-gray-700 mb-1">Permissions</label>
                    <div class="max-h-48 overflow-y-auto">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                                    class="mr-2" />
                                {{ $permission->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('selectedPermissions')
                        <span class="!text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button type="button" wire:click="$set('showCreateModal', false)"
                        class="px-4 py-2 !bg-gray-200 !text-gray-700 rounded-lg hover:!bg-gray-300">
                        Cancel
                    </flux:button>
                    <flux:button type="submit"
                        class="px-4 py-2 !bg-green-500 text-white rounded-lg hover:!bg-green-600">
                        {{ $roleId ? 'Update' : 'Create' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="$wire.showDeleteModal"
        class="fixed inset-0 !bg-gray-500 !bg-opacity-75 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-medium mb-4">Confirm Delete</h3>
            <p class="mb-4">Are you sure you want to delete this role?</p>
            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)"
                    class="px-4 py-2 !bg-gray-200 !text-gray-700 rounded-lg hover:!bg-gray-300">
                    Cancel
                </flux:button>
                <flux:button wire:click="delete" class="px-4 py-2 !bg-red-500 text-white rounded-lg hover:!bg-red-600">
                    Delete
                </flux:button>
            </div>
        </div>
    </div>
</div>
