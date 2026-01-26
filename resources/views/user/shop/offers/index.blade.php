@extends('user.shop.layout')

@section('title', 'Offers')
@section('page-title', 'Offers')

@section('shop-content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Offers & Promotions</h1>
            <p class="text-gray-600">Create time-limited offers for your products</p>
        </div>
        <a href="{{ route('user.shop.offers.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">
            <i class="fas fa-plus mr-2"></i> Create Offer
        </a>
    </div>
</div>

<!-- Offers List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($offers->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($offers as $offer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($offer->image)
                                        <img src="{{ Storage::url($offer->image) }}" alt="{{ $offer->title }}" class="w-12 h-12 rounded object-cover mr-3">
                                    @else
                                        <div class="w-12 h-12 rounded bg-gradient-to-br from-orange-400 to-pink-500 flex items-center justify-center mr-3">
                                            <i class="fas fa-tags text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $offer->title }}</p>
                                        <p class="text-sm text-gray-500">{{ Str::limit($offer->description, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-bold text-green-600">
                                    @if($offer->discount_type === 'percentage')
                                        {{ $offer->discount_value }}% OFF
                                    @else
                                        Rs. {{ number_format($offer->discount_value) }} OFF
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $offer->products_count ?? $offer->products->count() }} products</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <p class="text-gray-900">{{ $offer->starts_at->format('d M Y') }}</p>
                                    <p class="text-gray-500">to {{ $offer->end_date->format('d M Y') }}</p>
                                </div>
                                @if($offer->isActive())
                                    <span class="text-xs text-green-600">
                                        <i class="fas fa-clock"></i> {{ $offer->end_date->diffForHumans() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($offer->isActive())
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @elseif($offer->starts_at->isFuture())
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Scheduled</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('user.shop.offers.edit', $offer) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('user.shop.offers.destroy', $offer) }}" method="POST" class="inline" onsubmit="return confirm('Delete this offer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $offers->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto rounded-full bg-orange-100 flex items-center justify-center mb-4">
                <i class="fas fa-tags text-4xl text-orange-500"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No offers yet</h3>
            <p class="text-gray-500 mb-4">Create time-limited offers to boost sales</p>
            <a href="{{ route('user.shop.offers.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                <i class="fas fa-plus mr-2"></i> Create Your First Offer
            </a>
        </div>
    @endif
</div>
@endsection
