<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - {{ config('app.name') }}</title>
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
                    <a href="{{ route('user.profile') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-rose-50 hover:text-rose-700 transition-colors">
                        <i class="fas fa-user mr-3 text-lg"></i>
                        <span class="font-medium">Profile</span>
                    </a>
                    <a href="{{ route('user.settings') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg bg-rose-100 text-rose-800 border-r-4 border-rose-500">
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
                            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
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

                <div class="max-w-2xl">
                    <!-- Notification Settings -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Notification Settings</h3>
                        
                        <form action="{{ route('user.settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900">Email Notifications</label>
                                        <p class="text-sm text-gray-500">Receive email notifications for important updates</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" {{ $user->email_notifications ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-rose-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div>
                                        <label class="text-sm font-medium text-gray-900">Marketing Emails</label>
                                        <p class="text-sm text-gray-500">Receive promotional emails and offers</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="marketing_emails" value="1" class="sr-only peer" {{ $user->marketing_emails ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-rose-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                                    </label>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="bg-rose-600 text-white px-6 py-2 rounded-lg hover:bg-rose-700 transition">
                                        <i class="fas fa-save mr-2"></i>Save Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Account Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Account Information</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Account Status</label>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->status == 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $user->status == 1 ? 'Active' : 'Pending Approval' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Member Since</label>
                                </div>
                                <span class="text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>

                            <div class="flex items-center justify-between py-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-900">Email Verified</label>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                </span>
                            </div>
                        </div>
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
