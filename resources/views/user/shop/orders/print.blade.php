@extends('layouts.print')

@section('title', 'Print Order - ' . $order->id)

@section('content')
<div class="print-container">
    <!-- Header -->
    <div class="header-section">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold">{{ $shop->name }}</h1>
                <p class="text-sm text-gray-600">{{ $shop->description ?? 'Your trusted online store' }}</p>
                @if($shop->address)
                    <p class="text-sm text-gray-600">{{ $shop->address }}</p>
                @endif
                @if($shop->phone)
                    <p class="text-sm text-gray-600">Phone: {{ $shop->phone }}</p>
                @endif
                @if($shop->email)
                    <p class="text-sm text-gray-600">Email: {{ $shop->email }}</p>
                @endif
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold">ORDER RECEIPT</h2>
                <p class="text-sm">Order #: {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm">Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p class="text-sm">Status: {{ ucfirst($order->status) }}</p>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="customer-section mb-6">
        <h3 class="text-lg font-semibold mb-3">Customer Information</h3>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p><strong>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</strong></p>
                <p>{{ $order->customer->email }}</p>
                @if($order->customer->phone)
                    <p>{{ $order->customer->phone }}</p>
                @endif
            </div>
            @if($order->billing_address)
            <div>
                <p><strong>Billing Address:</strong></p>
                <p>{{ $order->billing_address->street_address }}</p>
                <p>{{ $order->billing_address->city }}, {{ $order->billing_address->state }} {{ $order->billing_address->postal_code }}</p>
                <p>{{ $order->billing_address->country }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="items-section mb-6">
        <h3 class="text-lg font-semibold mb-3">Order Items</h3>
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border border-gray-300 px-3 py-2 text-left">Item</th>
                    <th class="border border-gray-300 px-3 py-2 text-center">Qty</th>
                    <th class="border border-gray-300 px-3 py-2 text-right">Unit Price</th>
                    <th class="border border-gray-300 px-3 py-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td class="border border-gray-300 px-3 py-2">
                            <div>
                                <p class="font-medium">{{ $item->product->name }}</p>
                                @if($item->variant)
                                    <p class="text-sm text-gray-600">{{ $item->variant->name }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-center">{{ $item->quantity }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="border border-gray-300 px-3 py-2 text-right">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Order Summary -->
    <div class="summary-section">
        <div class="flex justify-end">
            <div class="w-64">
                <div class="border-t-2 border-gray-300 pt-2 space-y-1">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if($order->tax_amount > 0)
                        <div class="flex justify-between">
                            <span>Tax:</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($order->shipping_amount > 0)
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span>${{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span>Discount:</span>
                            <span>-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="border-t border-gray-300 pt-1 mt-2">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="payment-section mt-6">
        <h3 class="text-lg font-semibold mb-3">Payment Information</h3>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'Unknown') }}</p>
            </div>
            @if($order->notes)
            <div>
                <p><strong>Order Notes:</strong></p>
                <p>{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="footer-section mt-8 pt-4 border-t border-gray-300 text-center text-sm text-gray-600">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</div>

<style>
.print-container {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    line-height: 1.4;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

@media print {
    body {
        margin: 0;
        padding: 0;
    }

    .print-container {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 10px;
    }

    .no-print {
        display: none !important;
    }
}
</style>

<script>
window.onload = function() {
    window.print();
}
</script>
@endsection