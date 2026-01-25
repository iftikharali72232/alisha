@extends('layouts.admin')

@section('title', 'Comments')
@section('page-title', 'Comments')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Comments Management</h1>
    <p class="text-gray-600 mt-1">Moderate and manage blog comments</p>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Comment</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Author</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Post</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Status</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Date</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($comments as $comment)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-900 line-clamp-2 max-w-xs">{{ $comment->content }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $comment->name ?? ($comment->user->name ?? 'Anonymous') }}</p>
                            <p class="text-xs text-gray-500">{{ $comment->email ?? ($comment->user->email ?? '-') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.posts.show', $comment->post_id) }}" class="text-sm text-rose-600 hover:text-rose-700">
                            {{ Str::limit($comment->post->title ?? 'Unknown', 30) }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @if($comment->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Approved
                            </span>
                        @elseif($comment->status === 'spam')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-ban mr-1"></i>Spam
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $comment->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        @if($comment->status !== 'approved')
                        <form method="POST" action="{{ route('admin.comments.update', $comment) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors duration-200">
                                <i class="fas fa-check mr-1"></i>Approve
                            </button>
                        </form>
                        @endif
                        @if($comment->status !== 'spam')
                        <form method="POST" action="{{ route('admin.comments.spam', $comment) }}" class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors duration-200">
                                <i class="fas fa-ban mr-1"></i>Spam
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Delete this comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 font-medium">No comments yet</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($comments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection
