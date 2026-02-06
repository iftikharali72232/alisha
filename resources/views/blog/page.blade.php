@extends('layouts.blog')

@section('title', $page->title)
@section('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160))
@section('canonical_url', route('blog.page', $page->slug))

@section('content')
    <!-- Breadcrumb -->
    <nav class="bg-white border-b" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-rose-600 transition"><i class="fas fa-home"></i></a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900 font-medium">{{ $page->title }}</li>
            </ol>
        </div>
    </nav>

    <!-- Page Header -->
    @if($page->image)
    <div class="relative h-80 bg-gray-900">
        <img src="{{ asset('storage/' . $page->image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover opacity-80">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $page->title }}</h1>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-rose-500 to-purple-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $page->title }}</h1>
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12">
            <div class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-a:text-rose-600 prose-a:no-underline hover:prose-a:underline">
                {!! $page->content !!}
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .prose img {
        border-radius: 0.75rem;
    }
    .prose h2 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose p {
        margin-bottom: 1.25rem;
    }
    .prose ul, .prose ol {
        margin-left: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection
