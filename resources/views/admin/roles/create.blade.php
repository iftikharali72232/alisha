@extends('layouts.admin')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@section('content')
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    
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
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="e.g., Content Manager" required>
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
                            placeholder="Brief description of this role...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Create Role
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
                    <label class="flex items-center text-sm">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 mr-2">
                        Select All
                    </label>
                </div>
                
                <div class="space-y-6">
                    @foreach($permissions as $group => $groupPermissions)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <label class="flex items-center">
                                <input type="checkbox" class="group-checkbox w-4 h-4 text-rose-600 border-gray-300 rounded focus:ring-rose-500 mr-3" data-group="{{ $group }}">
                                <span class="text-sm font-semibold text-gray-700 capitalize">{{ ucfirst($group) }}</span>
                            </label>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($groupPermissions as $permission)
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->slug }}" 
                                    data-group="{{ $group }}"
                                    {{ in_array($permission->slug, old('permissions', [])) ? 'checked' : '' }}
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
    const groupCheckboxes = document.querySelectorAll('.group-checkbox');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    
    // Select All functionality
    selectAll.addEventListener('change', function() {
        permissionCheckboxes.forEach(cb => cb.checked = this.checked);
        groupCheckboxes.forEach(cb => cb.checked = this.checked);
    });
    
    // Group checkbox functionality
    groupCheckboxes.forEach(groupCb => {
        groupCb.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(cb => {
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
        const groupPerms = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
        const groupCb = document.querySelector(`.group-checkbox[data-group="${group}"]`);
        const allChecked = Array.from(groupPerms).every(cb => cb.checked);
        groupCb.checked = allChecked;
    }
    
    function updateSelectAll() {
        const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
        selectAll.checked = allChecked;
    }
});
</script>
@endsection
