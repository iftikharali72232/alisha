@extends('shops.layout')

@section('title', 'Login - ' . $shop->name)

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            @if($shop->logo)
                <img src="{{ Storage::url($shop->logo) }}" alt="{{ $shop->name }}" class="mx-auto h-16 w-auto">
            @endif
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Welcome Back</h2>
            <p class="mt-2 text-gray-600">Sign in to your account at {{ $shop->name }}</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('shop.login.submit', $shop->slug) }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="your@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded text-pink-500 focus:ring-pink-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-pink-600 hover:underline">Forgot password?</a>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-pink-600 text-white font-semibold rounded-lg hover:bg-pink-700 transition">
                    Sign In
                </button>
            </form>
        </div>

        <!-- Register Section -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">New Customer?</h3>
            <p class="text-gray-600 text-sm mb-4">Create an account to track orders, save addresses, and earn loyalty points!</p>
            
            <form action="{{ route('shop.register', $shop->slug) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="reg_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" id="reg_name" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        placeholder="Your full name">
                </div>

                <div>
                    <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" id="reg_email" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        placeholder="your@email.com">
                </div>

                <div>
                    <label for="reg_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="reg_phone"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        placeholder="+92 300 1234567">
                </div>

                <div>
                    <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="reg_password" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        placeholder="Min 8 characters">
                </div>

                <div>
                    <label for="reg_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="reg_password_confirmation" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                        placeholder="Confirm password">
                </div>

                <button type="submit"
                    class="w-full py-2 px-4 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 transition">
                    Create Account
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('shop.show', $shop->slug) }}" class="text-gray-600 hover:text-pink-600">
                <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
