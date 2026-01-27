@extends('layouts.admin')

@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')

@section('content')
<div class="max-w-4xl">
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="mb-4">
                    @if($user->avatar)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full mx-auto object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto bg-gradient-to-r from-rose-400 to-pink-400 flex items-center justify-center">
                            <span class="text-4xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                <p class="text-gray-600">{{ $user->email }}</p>
                <span class="inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium {{ $user->canAccessAdmin() ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $user->role?->name ?? ($user->canAccessAdmin() ? 'Administrator' : 'User') }}
                </span>
                @if($user->bio)
                <p class="text-sm text-gray-600 mt-4">{{ $user->bio }}</p>
                @endif
            </div>
        </div>

        <!-- Profile Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user text-rose-500 mr-2"></i>Basic Information
                </h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea name="bio" id="bio" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                            placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="+1 234 567 890">
                        </div>
                        
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $user->website) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="https://example.com">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                        
                        <!-- Current Avatar Preview -->
                        <div class="mb-4" id="avatarPreviewContainer">
                            @if($user->avatar)
                                <img src="{{ $user->avatar_url }}" alt="Current avatar" class="w-20 h-20 rounded-full object-cover border-2 border-rose-500" id="currentAvatar">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gradient-to-r from-rose-400 to-pink-400 flex items-center justify-center border-2 border-rose-500" id="currentAvatar">
                                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Avatar Selection -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Choose a pre-made avatar:</p>
                            <div class="flex flex-wrap gap-3">
                                @php
                                    $avatarStyles = [
                                        ['bg' => 'from-rose-400 to-pink-500', 'icon' => 'user'],
                                        ['bg' => 'from-blue-400 to-indigo-500', 'icon' => 'user-tie'],
                                        ['bg' => 'from-green-400 to-teal-500', 'icon' => 'user-astronaut'],
                                        ['bg' => 'from-purple-400 to-violet-500', 'icon' => 'user-ninja'],
                                        ['bg' => 'from-orange-400 to-red-500', 'icon' => 'user-secret'],
                                        ['bg' => 'from-cyan-400 to-blue-500', 'icon' => 'user-graduate'],
                                        ['bg' => 'from-yellow-400 to-orange-500', 'icon' => 'smile'],
                                        ['bg' => 'from-pink-400 to-rose-500', 'icon' => 'heart'],
                                    ];
                                @endphp
                                @foreach($avatarStyles as $index => $style)
                                <label class="cursor-pointer">
                                    <input type="radio" name="avatar_preset" value="{{ $index }}" class="hidden peer">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r {{ $style['bg'] }} flex items-center justify-center peer-checked:ring-4 ring-rose-300 transition hover:scale-110">
                                        <i class="fas fa-{{ $style['icon'] }} text-white text-lg"></i>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Or upload custom avatar -->
                        <div class="flex items-center space-x-4">
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                            <button type="button" onclick="document.getElementById('avatar').click()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-upload mr-2"></i>Upload Custom Photo
                            </button>
                            <span id="avatarFileName" class="text-sm text-gray-500">No file chosen</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Social Links -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-share-alt text-rose-500 mr-2"></i>Social Links
                </h3>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-facebook text-blue-600 mr-1"></i>Facebook
                            </label>
                            <input type="text" name="facebook" id="facebook" value="{{ old('facebook', $user->facebook) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="username">
                        </div>
                        
                        <div>
                            <label for="twitter" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-twitter text-blue-400 mr-1"></i>Twitter
                            </label>
                            <input type="text" name="twitter" id="twitter" value="{{ old('twitter', $user->twitter) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="@username">
                        </div>
                        
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fab fa-instagram text-pink-600 mr-1"></i>Instagram
                            </label>
                            <input type="text" name="instagram" id="instagram" value="{{ old('instagram', $user->instagram) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                                placeholder="@username">
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                            <i class="fas fa-save mr-2"></i>Save Social Links
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-lock text-rose-500 mr-2"></i>Change Password
                </h3>
                
                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition font-medium">
                            <i class="fas fa-key mr-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
    document.getElementById('avatarFileName').textContent = fileName;
    
    // Preview uploaded image
    if (e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const container = document.getElementById('avatarPreviewContainer');
            container.innerHTML = `<img src="${event.target.result}" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-rose-500">`;
        };
        reader.readAsDataURL(e.target.files[0]);
        
        // Deselect preset avatars
        document.querySelectorAll('input[name="avatar_preset"]').forEach(radio => {
            radio.checked = false;
        });
    }
});

// Handle preset avatar selection
document.querySelectorAll('input[name="avatar_preset"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Clear custom file input
        document.getElementById('avatar').value = '';
        document.getElementById('avatarFileName').textContent = 'No file chosen';
    });
});
</script>
@endsection
