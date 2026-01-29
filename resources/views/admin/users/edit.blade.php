@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)

@section('content')
<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Role
                    </label>
                    <select name="role_id" id="role_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        <option value="">No Role (Regular User)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }} {{ $role->isSuperAdmin() ? 'disabled' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Assigning a role will grant admin panel access based on role permissions.</p>
                </div>
                
                <!-- Admin Status -->
                <div class="flex items-center space-x-6">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                            class="w-5 h-5 text-rose-600 border-gray-300 rounded focus:ring-rose-500">
                        <span class="text-sm text-gray-700">Is Admin</span>
                    </label>
                    
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="status" value="1" {{ old('status', $user->status) ? 'checked' : '' }}
                            class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
                
                <!-- User Info -->
                <div class="pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">User Information</h4>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Registered:</dt>
                            <dd class="text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Last Updated:</dt>
                            <dd class="text-gray-900">{{ $user->updated_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Posts:</dt>
                            <dd class="text-gray-900">{{ $user->posts()->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Comments:</dt>
                            <dd class="text-gray-900">{{ $user->comments()->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200 flex gap-3">
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
