@extends('layouts.blog')

@section('title', 'Contact Us')
@section('meta_description', 'Get in touch with Vision Sphere. Have a question, want to collaborate, or need support? We would love to hear from you.')
@section('canonical_url', route('blog.contact'))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-rose-600 transition"><i class="fas fa-home"></i></a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900 font-medium">Contact Us</li>
            </ol>
        </div>
    </nav>

    <!-- Contact Header -->
    <div class="bg-gradient-to-r from-rose-500 to-purple-600 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Contact Us</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Have a question or want to work with us? We'd love to hear from you!</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                    <p class="text-gray-600">We're here to help and answer any question you might have. We look forward to hearing from you!</p>
                </div>

                <div class="space-y-6">
                    @if(\App\Models\Setting::get('contact_email'))
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-rose-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <a href="mailto:{{ \App\Models\Setting::get('contact_email') }}" class="text-gray-600 hover:text-rose-600 transition">
                                {{ \App\Models\Setting::get('contact_email') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(\App\Models\Setting::get('contact_phone'))
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-rose-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Phone</h3>
                            <a href="tel:{{ \App\Models\Setting::get('contact_phone') }}" class="text-gray-600 hover:text-rose-600 transition">
                                {{ \App\Models\Setting::get('contact_phone') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(\App\Models\Setting::get('contact_address'))
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-rose-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Address</h3>
                            <p class="text-gray-600">{{ \App\Models\Setting::get('contact_address') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Social Links -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        @if(\App\Models\Setting::get('facebook_url'))
                        <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank" class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('twitter_url'))
                        <a href="{{ \App\Models\Setting::get('twitter_url') }}" target="_blank" class="w-12 h-12 bg-sky-500 text-white rounded-xl flex items-center justify-center hover:bg-sky-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('instagram_url'))
                        <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank" class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 text-white rounded-xl flex items-center justify-center hover:from-purple-700 hover:to-pink-600 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                        @if(\App\Models\Setting::get('youtube_url'))
                        <a href="{{ \App\Models\Setting::get('youtube_url') }}" target="_blank" class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center hover:bg-red-700 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm p-8 md:p-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                    
                    @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('blog.contact.send') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea name="message" id="message" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="w-full md:w-auto px-8 py-3 bg-rose-600 text-white rounded-xl font-medium hover:bg-rose-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Find quick answers to common questions.</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="font-medium text-gray-900">How can I submit a guest post?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">We welcome guest contributions! Please send your article pitch to our email along with a brief bio. We'll review it and get back to you within 7 business days.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="font-medium text-gray-900">Do you offer advertising opportunities?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">Yes, we offer various advertising options including sponsored posts, banner ads, and newsletter sponsorships. Contact us for rates and availability.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="font-medium text-gray-900">How quickly do you respond to inquiries?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">We typically respond to all inquiries within 24-48 hours during business days. For urgent matters, please mention it in your subject line.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <button class="faq-toggle w-full px-6 py-4 text-left flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="font-medium text-gray-900">Can I republish your content?</span>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-gray-600">Our content is protected by copyright. However, you may share snippets with proper attribution and a link back to the original article. For full republishing rights, please contact us.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function toggleFaq(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endsection
