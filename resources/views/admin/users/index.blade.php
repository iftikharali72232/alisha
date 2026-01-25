<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800">Admin Panel</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ url('/admin/dashboard') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-200">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-200">
                    <i class="fas fa-newspaper mr-2"></i>Posts
                </a>
                <a href="{{ route('admin.users.index') }}" class="block px-6 py-3 bg-blue-100 text-blue-700">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-gray-200">
                    <i class="fas fa-folder mr-2"></i>Categories
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-gray-200">
                    <i class="fas fa-tags mr-2"></i>Tags
                </a>
                <a href="#" class="block px-6 py-3 text-gray-700 hover:bg-gray-200">
                    <i class="fas fa-comments mr-2"></i>Comments
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-800">Users</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Users List -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Admin</th>
                                <th class="px-4 py-2 text-left">Created At</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $user->id }}</td>
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $user->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $user->is_admin ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-{{ $user->status == 1 ? 'red' : 'green' }}-500 hover:bg-{{ $user->status == 1 ? 'red' : 'green' }}-600 text-white px-3 py-1 rounded text-sm mr-2">
                                            {{ $user->status == 1 ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-{{ $user->is_admin ? 'gray' : 'blue' }}-500 hover:bg-{{ $user->is_admin ? 'gray' : 'blue' }}-600 text-white px-3 py-1 rounded text-sm">
                                            {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>