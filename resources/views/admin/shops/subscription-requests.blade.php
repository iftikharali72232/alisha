@extends('layouts.admin')

@section('title', 'Subscription Requests')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Subscription Requests</h1>
            <p class="text-gray-600">Manage pending subscription requests from shop owners</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Shop</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Owner</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Plan</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Requested</th>
                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($requests as $request)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            @if($request->shop->logo)
                                <img src="{{ Storage::url($request->shop->logo) }}" alt="{{ $request->shop->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-store text-pink-500"></i>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('admin.shops.show', $request->shop) }}" class="font-medium text-gray-800 hover:text-pink-600">
                                    {{ $request->shop->name }}
                                </a>
                                <div class="text-xs text-gray-500">{{ $request->shop->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div>{{ $request->shop->user->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $request->shop->user->email ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $request->plan->name }}</div>
                        <div class="text-xs text-gray-500">Rs. {{ number_format($request->plan->price) }} / {{ $request->plan->duration }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div>{{ $request->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $request->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <!-- Approve Button -->
                            <button onclick="openApproveModal({{ $request->id }}, '{{ $request->shop->name }}', '{{ $request->plan->name }}')"
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium px-3 py-1 rounded text-sm transition-colors duration-200">
                                <i class="fas fa-check mr-1"></i> Approve
                            </button>

                            <!-- Reject Button -->
                            <form action="{{ route('admin.subscription-requests.reject', $request) }}" method="POST" class="inline">
                                @csrf
                                @method('POST')
                                <button type="submit" onclick="return confirm('Are you sure you want to reject this subscription request?')"
                                    class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium px-3 py-1 rounded text-sm transition-colors duration-200">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <div>No pending subscription requests</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    @endif
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Approve Subscription Request</h3>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="approveForm" action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Shop</label>
                    <div id="modalShopName" class="text-sm text-gray-600"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                    <div id="modalPlanName" class="text-sm text-gray-600"></div>
                </div>

                <div class="mb-4">
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" id="starts_at" name="starts_at" required
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>

                <div class="mb-4">
                    <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                    <input type="date" id="ends_at" name="ends_at" required
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>

                <div class="mb-4">
                    <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                    <input type="number" id="amount_paid" name="amount_paid" step="0.01" min="0"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="payment_method" name="payment_method"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="online">Online Payment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                    <input type="text" id="transaction_id" name="transaction_id"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Approve Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApproveModal(subscriptionId, shopName, planName) {
    document.getElementById('modalShopName').textContent = shopName;
    document.getElementById('modalPlanName').textContent = planName;
    document.getElementById('approveForm').action = `/admin/subscription-requests/${subscriptionId}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');

    // Set default dates
    const today = new Date();
    const startDate = new Date(today);
    startDate.setDate(today.getDate() + 1); // Tomorrow
    const endDate = new Date(startDate);
    endDate.setFullYear(endDate.getFullYear() + 1); // 1 year later

    document.getElementById('starts_at').value = startDate.toISOString().split('T')[0];
    document.getElementById('ends_at').value = endDate.toISOString().split('T')[0];
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}
</script>
@endsection