@extends('shops.layout')

@section('title', 'Contact Us - ' . $shop->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Contact Us</h1>
        <p class="text-gray-600">Get in touch with us. We're here to help!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Send us a message</h2>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('blog.contact.send') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Your full name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="your@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject *</label>
                    <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                        placeholder="What's this about?">
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('message') border-red-500 @enderror"
                        placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-pink-600 text-white py-3 px-6 rounded-lg hover:bg-pink-700 font-medium transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i> Send Message
                </button>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="space-y-6">
            <!-- Contact Details -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Get in touch</h2>

                <div class="space-y-4">
                    @if($shop->email)
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-pink-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-800">Email</p>
                                <p class="text-gray-600">{{ $shop->email }}</p>
                            </div>
                        </div>
                    @endif

                    @if($shop->phone)
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-800">Phone</p>
                                <p class="text-gray-600">{{ $shop->phone }}</p>
                            </div>
                        </div>
                    @endif

                    @if($shop->whatsapp_number)
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fab fa-whatsapp text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-800">WhatsApp</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shop->whatsapp_number) }}"
                                   class="text-gray-600 hover:text-green-600 transition-colors">{{ $shop->whatsapp_number }}</a>
                            </div>
                        </div>
                    @endif

                    @if($shop->address)
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-gray-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-800">Address</p>
                                <p class="text-gray-600">{{ $shop->address }}</p>
                                @if($shop->city)
                                    <p class="text-gray-600">{{ $shop->city }}, {{ $shop->country }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Business Hours -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Business Hours</h2>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Monday - Friday</span>
                        <span class="font-medium">9:00 AM - 6:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Saturday</span>
                        <span class="font-medium">10:00 AM - 4:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sunday</span>
                        <span class="font-medium">Closed</span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        We typically respond to inquiries within 24 hours.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection