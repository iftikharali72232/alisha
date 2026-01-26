<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen lg:flex">
        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden" onclick="toggleMobileMenu()"></div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full lg:translate-x-0 lg:static lg:z-auto overflow-y-auto transition-transform duration-300">
            <div class="flex flex-col h-full">
                <!-- Logo/Brand -->
                <div class="flex items-center justify-between p-6 border-b border-rose-200">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/logo.svg?v=' . time()) }}" alt="Logo" class="w-14 h-14 rounded-lg shadow-sm">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ config('app.name') }}</h2>
                            <p class="text-xs text-gray-500">User Dashboard</p>
                        </div>
                    </div>
                    <button onclick="toggleMobileMenu()" class="lg:hidden text-gray-500 hover:text-rose-600 p-2 rounded-md">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('user.dashboard') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors">
                        <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    
                    <!-- My Shop Section -->
                    <div class="pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">My Shop</span>
                    </div>
                    @php $userShop = auth()->user()->shop; @endphp
                    @if($userShop)
                        <a href="{{ route('user.shop.dashboard') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors">
                            <i class="fas fa-store mr-3 text-lg"></i>
                            <span class="font-medium">{{ Str::limit($userShop->name, 15) }}</span>
                        </a>
                    @else
                        <a href="{{ route('user.shop.create') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors">
                            <i class="fas fa-plus-circle mr-3 text-lg"></i>
                            <span class="font-medium">Create Shop</span>
                        </a>
                    @endif
                    
                    <!-- Account Section -->
                    <div class="pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Account</span>
                    </div>
                    <a href="{{ route('user.profile') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg bg-rose-100 text-rose-800 border-r-4 border-rose-500">
                        <i class="fas fa-user mr-3 text-lg"></i>
                        <span class="font-medium">Profile</span>
                    </a>
                    <a href="{{ route('user.settings') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors">
                        <i class="fas fa-cog mr-3 text-lg"></i>
                        <span class="font-medium">Settings</span>
                    </a>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t border-rose-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-rose-400 to-pink-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-rose-600">{{ auth()->user()->status == 1 ? 'Active' : 'Pending' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-h-screen">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-rose-200 sticky top-0 z-30">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <button onclick="toggleMobileMenu()" class="lg:hidden mr-4 text-gray-500 hover:text-rose-600 p-2 rounded-md">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-rose-600">
                                    <i class="fas fa-sign-out-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Profile Card -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Profile Information</h3>
                        
                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                <!-- Profile Image -->
                                <div class="flex items-center space-x-6">
                                    <div class="relative">
                                        @if($user->profile_image)
                                            <img src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover">
                                        @else
                                            <div class="w-24 h-24 rounded-full bg-gradient-to-r from-rose-400 to-pink-400 flex items-center justify-center">
                                                <span class="text-white text-3xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                        <input type="file" name="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                                    </div>
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500 @error('name') border-red-500 @enderror">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500 @error('email') border-red-500 @enderror">
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500">
                                </div>

                                <!-- Bio -->
                                <div>
                                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                    <textarea name="bio" id="bio" rows="4" 
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500">{{ old('bio', $user->bio) }}</textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-lg hover:bg-rose-700 transition">
                                        <i class="fas fa-save mr-2"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Password Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Change Password</h3>
                        
                        <form action="{{ route('user.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" required
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500">
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <input type="password" name="password" id="password" required
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500">
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full border rounded-lg px-4 py-2 focus:ring-rose-500 focus:border-rose-500">
                                </div>

                                <button type="submit" class="w-full bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition">
                                    <i class="fas fa-key mr-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
