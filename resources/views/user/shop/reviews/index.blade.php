@extends('user.shop.layout')

@section('title', 'Reviews - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Product Reviews</h1>
            <p class="text-gray-600">Manage customer reviews and ratings</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-star text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['average'], 1) }}</p>
                <p class="text-sm text-gray-500">Average Rating</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-comments text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                <p class="text-sm text-gray-500">Total Reviews</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['approved'] }}</p>
                <p class="text-sm text-gray-500">Approved</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="fas fa-clock text-orange-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
                <p class="text-sm text-gray-500">Pending</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                placeholder="Search by product or customer...">
        </div>

        <select name="status" class="border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <select name="rating" class="border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500">
            <option value="">All Ratings</option>
            <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
            <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
            <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
            <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
            <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
        </select>

        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
            <i class="fas fa-search mr-2"></i>Filter
        </button>

        @if(request()->hasAny(['search', 'status', 'rating']))
            <a href="{{ route('user.shop.reviews.index') }}" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times mr-1"></i>Clear
            </a>
        @endif
    </form>
</div>

<!-- Reviews List -->
<div class="space-y-4">
    @forelse($reviews as $review)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col md:flex-row md:items-start gap-4">
                <!-- Product Info -->
                <div class="flex-shrink-0 w-full md:w-48">
                    <a href="{{ route('user.shop.products.show', $review->product) }}" 
                        class="flex items-center gap-3 hover:opacity-80 transition">
                        @if($review->product->featured_image)
                            <img src="{{ Storage::url($review->product->featured_image) }}" 
                                alt="{{ $review->product->name }}"
                                class="w-16 h-16 rounded-lg object-cover">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate">{{ $review->product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $shop->currency }} {{ number_format($review->product->price) }}</p>
                        </div>
                    </a>
                </div>

                <!-- Review Content -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <!-- Rating Stars -->
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <!-- Status Badge -->
                            @if($review->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs">Pending</span>
                            @elseif($review->status === 'approved')
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">Approved</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">Rejected</span>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Customer Info -->
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-semibold text-sm">
                            {{ strtoupper(substr($review->customer->name ?? 'G', 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $review->customer->name ?? 'Guest' }}</span>
                        @if($review->verified_purchase)
                            <span class="text-green-600 text-sm"><i class="fas fa-check-circle mr-1"></i>Verified Purchase</span>
                        @endif
                    </div>

                    @if($review->title)
                        <h4 class="font-semibold text-gray-800 mb-1">{{ $review->title }}</h4>
                    @endif

                    <p class="text-gray-600">{{ $review->comment }}</p>

                    @if($review->images && count($review->images) > 0)
                        <div class="flex gap-2 mt-3">
                            @foreach($review->images as $image)
                                <img src="{{ Storage::url($image) }}" alt="Review image"
                                    class="w-16 h-16 rounded-lg object-cover cursor-pointer hover:opacity-80"
                                    onclick="openImageModal('{{ Storage::url($image) }}')">
                            @endforeach
                        </div>
                    @endif

                    <!-- Reply -->
                    @if($review->reply)
                        <div class="mt-4 pl-4 border-l-2 border-pink-200">
                            <p class="text-sm font-medium text-pink-600 mb-1">
                                <i class="fas fa-reply mr-1"></i>Your Reply
                            </p>
                            <p class="text-sm text-gray-600">{{ $review->reply }}</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex-shrink-0 flex flex-col gap-2">
                    @if($review->status === 'pending')
                        <form action="{{ route('user.shop.reviews.approve', $review) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full px-3 py-1.5 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition">
                                <i class="fas fa-check mr-1"></i>Approve
                            </button>
                        </form>
                        <form action="{{ route('user.shop.reviews.reject', $review) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full px-3 py-1.5 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                <i class="fas fa-times mr-1"></i>Reject
                            </button>
                        </form>
                    @endif
                    
                    @if(!$review->reply)
                        <button onclick="openReplyModal({{ $review->id }})"
                            class="px-3 py-1.5 border text-gray-700 rounded-lg text-sm hover:bg-gray-50 transition">
                            <i class="fas fa-reply mr-1"></i>Reply
                        </button>
                    @endif

                    <form action="{{ route('user.shop.reviews.destroy', $review) }}" method="POST"
                        onsubmit="return confirm('Delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-1.5 text-red-500 border border-red-200 rounded-lg text-sm hover:bg-red-50 transition">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-star text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-700 mb-2">No reviews yet</h3>
            <p class="text-gray-500">Customer reviews will appear here once they start reviewing products</p>
        </div>
    @endforelse
</div>

@if($reviews->hasPages())
    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
@endif

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Reply to Review</h3>
            <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="replyForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="reply" class="block text-sm font-medium text-gray-700 mb-1">Your Reply</label>
                <textarea name="reply" id="replyText" rows="4" required
                    class="w-full border rounded-lg px-4 py-2 focus:ring-pink-500 focus:border-pink-500"
                    placeholder="Write a helpful response to this review..."></textarea>
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeReplyModal()"
                    class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition">
                    Send Reply
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-50" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Review image" class="max-w-full max-h-full object-contain">
</div>

@push('scripts')
<script>
function openReplyModal(reviewId) {
    const modal = document.getElementById('replyModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('replyForm').action = `/user/shop/reviews/${reviewId}/reply`;
    document.getElementById('replyText').value = '';
}

function closeReplyModal() {
    const modal = document.getElementById('replyModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    document.getElementById('modalImage').src = src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modals on outside click
document.getElementById('replyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReplyModal();
    }
});
</script>
@endpush
@endsection
