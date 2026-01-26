@extends('user.shop.layout')

@section('title', 'Order Invoice - ' . $shop->name)

@section('shop-content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Order Invoice</h1>
            <p class="text-gray-600">Invoice for Order #{{ $order->id }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-print mr-2"></i>Print Invoice
            </button>
            <a href="{{ route('user.shop.orders.show', $order) }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to Order
            </a>
        </div>
    </div>
</div>

<!-- Invoice Content -->
<div class="bg-white rounded-lg shadow p-8 max-w-4xl mx-auto" id="invoice-content">
    <!-- Header -->
    <div class="border-b-2 border-gray-200 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $shop->name }}</h2>
                <p class="text-gray-600 mt-1">{{ $shop->description ?? 'Your trusted online store' }}</p>
                @if($shop->address)
                    <p class="text-gray-600 mt-1">{{ $shop->address }}</p>
                @endif
                @if($shop->phone)
                    <p class="text-gray-600">Phone: {{ $shop->phone }}</p>
                @endif
                @if($shop->email)
                    <p class="text-gray-600">Email: {{ $shop->email }}</p>
                @endif
            </div>
            <div class="text-right">
                <h3 class="text-2xl font-bold text-gray-800">INVOICE</h3>
                <p class="text-gray-600 mt-1">Invoice #: {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-gray-600">Date: {{ $order->created_at->format('M d, Y') }}</p>
                <p class="text-gray-600">Due Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Billing & Shipping Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Bill To -->
        <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Bill To:</h4>
            <div class="text-gray-700">
                <p class="font-medium">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                <p>{{ $order->customer->email }}</p>
                @if($order->customer->phone)
                    <p>{{ $order->customer->phone }}</p>
                @endif
                @if($order->billing_address)
                    <p class="mt-2">{{ $order->billing_address->street_address }}</p>
                    <p>{{ $order->billing_address->city }}, {{ $order->billing_address->state }} {{ $order->billing_address->postal_code }}</p>
                    <p>{{ $order->billing_address->country }}</p>
                @endif
            </div>
        </div>

        <!-- Ship To -->
        @if($order->shipping_address)
        <div>
            <h4 class="text-lg font-semibold text-gray-800 mb-3">Ship To:</h4>
            <div class="text-gray-700">
                <p class="font-medium">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                <p>{{ $order->shipping_address->street_address }}</p>
                <p>{{ $order->shipping_address->city }}, {{ $order->shipping_address->state }} {{ $order->shipping_address->postal_code }}</p>
                <p>{{ $order->shipping_address->country }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="mb-8">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h4>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left py-3 px-2 font-semibold text-gray-800">Item</th>
                        <th class="text-center py-3 px-2 font-semibold text-gray-800">Qty</th>
                        <th class="text-right py-3 px-2 font-semibold text-gray-800">Unit Price</th>
                        <th class="text-right py-3 px-2 font-semibold text-gray-800">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-4 px-2">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                                    @if($item->variant)
                                        <p class="text-sm text-gray-600">{{ $item->variant->name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center py-4 px-2 text-gray-700">{{ $item->quantity }}</td>
                            <td class="text-right py-4 px-2 text-gray-700">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right py-4 px-2 text-gray-700">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="flex justify-end mb-8">
        <div class="w-64">
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                </div>

                @if($order->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax:</span>
                        <span class="text-gray-800">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                @endif

                @if($order->shipping_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping:</span>
                        <span class="text-gray-800">${{ number_format($order->shipping_amount, 2) }}</span>
                    </div>
                @endif

                @if($order->discount_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Discount:</span>
                        <span class="text-red-600">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif

                <div class="border-t-2 border-gray-200 pt-2 mt-2">
                    <div class="flex justify-between text-lg font-bold">
                        <span class="text-gray-800">Total:</span>
                        <span class="text-gray-800">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="mb-8">
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Payment Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600">Payment Method:</p>
                <p class="text-gray-800 font-medium">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Payment Status:</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->payment_status === 'failed') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($order->payment_status ?? 'unknown') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="border-t-2 border-gray-200 pt-6">
        <div class="text-center text-gray-600">
            <p>Thank you for your business!</p>
            <p class="mt-1">For any questions about this invoice, please contact us.</p>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #invoice-content, #invoice-content * {
        visibility: visible;
    }
    #invoice-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection