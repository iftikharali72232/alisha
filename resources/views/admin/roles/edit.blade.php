@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role: ' . $role->name)

@section('content')
<form action="{{ route('admin.roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Role Details -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Role Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent {{ $role->slug === 'super-admin' ? 'bg-gray-100' : '' }}"
                            placeholder="e.g., Content Manager" {{ $role->slug === 'super-admin' ? 'readonly' : '' }} required>
                        @if($role->slug === 'super-admin')
                            <p class="text-sm text-gray-500 mt-1">Super Admin role name cannot be changed.</p>
                        @endif
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="Brief description of this role...">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Role Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Role Information</h4>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Created:</dt>
                            <dd class="text-gray-900">{{ $role->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Users:</dt>
                            <dd class="text-gray-900">{{ $role->users()->count() }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Role
                        </button>
                    </div>
                    <a href="{{ route('admin.roles.index') }}" class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Permissions -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                    @if($role->slug === 'super-admin')
                        <span class="text-sm text-gray-500">Super Admin has all permissions</span>
                    @else
                        <label class="flex items-center text-sm">
                            <input type="checkbox" id="select-all" class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 mr-2">
                            Select All
                        </label>
                    @endif
                </div>
                
                <div class="space-y-6">
                    @foreach($permissions as $group => $groupPermissions)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <label class="flex items-center">
                                <input type="checkbox" class="group-checkbox w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 mr-3" 
                                    data-group="{{ $group }}"
                                    {{ $role->slug === 'super-admin' ? 'checked disabled' : '' }}>
                                <span class="text-sm font-semibold text-gray-700 capitalize">{{ ucfirst($group) }}</span>
                            </label>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($groupPermissions as $permission)
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->slug }}" 
                                    data-group="{{ $group }}"
                                    {{ in_array($permission->slug, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                    {{ $role->slug === 'super-admin' ? 'checked disabled' : '' }}
                                    class="permission-checkbox w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 mt-0.5">
                                <div>
                                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                    @if($permission->description)
                                        <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const groupCheckboxes = document.querySelectorAll('.group-checkbox:not([disabled])');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox:not([disabled])');
    
    if (!selectAll) return;
    
    // Initialize group checkboxes state
    groupCheckboxes.forEach(groupCb => {
        updateGroupCheckbox(groupCb.dataset.group);
    });
    updateSelectAll();
    
    // Select All functionality
    selectAll.addEventListener('change', function() {
        permissionCheckboxes.forEach(cb => cb.checked = this.checked);
        groupCheckboxes.forEach(cb => cb.checked = this.checked);
    });
    
    // Group checkbox functionality
    groupCheckboxes.forEach(groupCb => {
        groupCb.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll(`.permission-checkbox:not([disabled])[data-group="${group}"]`).forEach(cb => {
                cb.checked = this.checked;
            });
            updateSelectAll();
        });
    });
    
    // Update group checkbox when permission is toggled
    permissionCheckboxes.forEach(permCb => {
        permCb.addEventListener('change', function() {
            updateGroupCheckbox(this.dataset.group);
            updateSelectAll();
        });
    });
    
    function updateGroupCheckbox(group) {
        const groupPerms = document.querySelectorAll(`.permission-checkbox:not([disabled])[data-group="${group}"]`);
        const groupCb = document.querySelector(`.group-checkbox:not([disabled])[data-group="${group}"]`);
        if (!groupCb || groupPerms.length === 0) return;
        const allChecked = Array.from(groupPerms).every(cb => cb.checked);
        groupCb.checked = allChecked;
    }
    
    function updateSelectAll() {
        if (permissionCheckboxes.length === 0) return;
        const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
        selectAll.checked = allChecked;
    }
});
</script>
@endsection
