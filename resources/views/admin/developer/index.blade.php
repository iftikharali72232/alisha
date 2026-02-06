@extends('layouts.admin')

@section('title', 'Developer Information')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Developer Information</h1>
        <p class="text-gray-600">Platform creator and technical details</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <!-- Developer Profile -->
        <div class="col-span-1">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                    <span class="text-4xl font-bold text-white">{{ substr($developer['name'], 0, 1) }}</span>
                </div>
                
                <h2 class="text-xl font-bold text-gray-800">{{ $developer['name'] }}</h2>
                <p class="text-pink-600 font-medium">{{ $developer['role'] }}</p>
                <p class="text-gray-500 text-sm mt-1">{{ $developer['company'] }}</p>
                
                <div class="flex justify-center gap-3 mt-4">
                    @if(!empty($developer['github']))
                        <a href="{{ $developer['github'] }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-800 text-white flex items-center justify-center hover:bg-gray-700">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif
                    @if(!empty($developer['linkedin']))
                        <a href="{{ $developer['linkedin'] }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if(!empty($developer['website']))
                        <a href="{{ $developer['website'] }}" target="_blank" class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center hover:bg-pink-700">
                            <i class="fas fa-globe"></i>
                        </a>
                    @endif
                </div>
                
                <div class="mt-6 text-left space-y-3 text-sm">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-envelope w-6 text-gray-400"></i>
                        <span>{{ $developer['email'] }}</span>
                    </div>
                    @if(!empty($developer['phone']))
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-6 text-gray-400"></i>
                            <span>{{ $developer['phone'] }}</span>
                        </div>
                    @endif
                    @if(!empty($developer['whatsapp']))
                        <div class="flex items-center text-gray-600">
                            <i class="fab fa-whatsapp w-6 text-green-500"></i>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $developer['whatsapp']) }}" target="_blank" class="text-green-600 hover:underline">
                                {{ $developer['whatsapp'] }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t">
                    <p class="text-gray-600 text-sm">{{ $developer['bio'] }}</p>
                </div>
            </div>
        </div>

        <!-- Skills & Projects -->
        <div class="col-span-2">
            <!-- Technical Skills -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-code text-pink-500 mr-2"></i> Technical Skills
                </h3>
                
                <div class="grid grid-cols-2 gap-6">
                    @foreach($developer['skills'] as $category => $skills)
                        <div>
                            <h4 class="text-sm font-medium text-gray-600 mb-2">{{ $category }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Projects -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-project-diagram text-pink-500 mr-2"></i> Projects
                </h3>
                
                @foreach($developer['projects'] as $project)
                    <div class="p-4 bg-gray-50 rounded-lg mb-4 last:mb-0">
                        <div class="flex justify-between items-start">
                            <h4 class="font-medium text-gray-800">{{ $project['name'] }}</h4>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">{{ $project['status'] }}</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-2">{{ $project['description'] }}</p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($project['technologies'] as $tech)
                                <span class="px-2 py-1 bg-pink-100 text-pink-700 rounded text-xs">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-server text-pink-500 mr-2"></i> System Information
                </h3>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500">PHP Version</span>
                        <p class="font-medium text-gray-800">{{ $systemInfo['php_version'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500">Laravel Version</span>
                        <p class="font-medium text-gray-800">{{ $systemInfo['laravel_version'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500">Server</span>
                        <p class="font-medium text-gray-800">{{ $systemInfo['server_software'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500">Database</span>
                        <p class="font-medium text-gray-800">{{ ucfirst($systemInfo['database']) }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500">Cache Driver</span>
                        <p class="font-medium text-gray-800">{{ ucfirst($systemInfo['cache_driver']) }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500">Session Driver</span>
                        <p class="font-medium text-gray-800">{{ ucfirst($systemInfo['session_driver']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Support Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-headset text-pink-500 mr-2"></i> Support
                </h3>
                
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div class="p-4 bg-blue-50 rounded-lg text-center">
                        <i class="fas fa-envelope text-2xl text-blue-500 mb-2"></i>
                        <p class="text-gray-600">Support Email</p>
                        <p class="font-medium text-gray-800">{{ $developer['support']['email'] }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg text-center">
                        <i class="fas fa-clock text-2xl text-green-500 mb-2"></i>
                        <p class="text-gray-600">Response Time</p>
                        <p class="font-medium text-gray-800">{{ $developer['support']['response_time'] }}</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg text-center">
                        <i class="fas fa-business-time text-2xl text-purple-500 mb-2"></i>
                        <p class="text-gray-600">Working Hours</p>
                        <p class="font-medium text-gray-800">{{ $developer['support']['working_hours'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Footer -->
    <div class="mt-8 text-center py-6 border-t">
        <p class="text-gray-500">
            <strong class="text-pink-600">Vision Sphere</strong> - Explore Your World of Ideas
        </p>
        <p class="text-sm text-gray-400 mt-1">
            Built with ❤️ using Laravel, Alpine.js, and Tailwind CSS
        </p>
        <p class="text-xs text-gray-400 mt-2">
            © {{ date('Y') }} All rights reserved. Version 1.0.0
        </p>
    </div>
</div>
@endsection
