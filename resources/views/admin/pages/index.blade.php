@extends('layouts.admin')

@section('title', 'Pages')
@section('page-title', 'Pages')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <p class="text-gray-600">Manage static pages like About, Privacy Policy, and Terms of Service.</p>
    <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
        <i class="fas fa-plus mr-2"></i>Create Page
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pages as $page)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 text-sm font-medium">
                            {{ $page->order }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($page->featured_image)
                            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-10 h-10 rounded object-cover mr-3">
                            @else
                            <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-gray-400"></i>
                            </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $page->title }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="text-sm bg-gray-100 px-2 py-1 rounded">/{{ $page->slug }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($page->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-times-circle mr-1"></i>Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/page/{{ $page->slug }}" target="_blank" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition" title="View">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">No pages created yet.</p>
                            <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
                                <i class="fas fa-plus mr-2"></i>Create First Page
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($pages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $pages->links() }}
    </div>
    @endif
</div>
@endsection
