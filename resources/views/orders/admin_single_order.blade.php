
@section('title', 'Order Details')

<x-admin>
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Order #{{ $order->id }}
            </h1>
            <p class="text-gray-500 text-sm">
                Placed on {{ $order->created_at->format('d M Y - h:i A') }}
            </p>
        </div>

        <!-- Status Update -->
        <div>
            <select id="statusSelect"
                    class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">

                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Processing</option>
                <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Completed</option>
                <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
        </div>
    </div>


    <!-- Order + Customer Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Customer Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Customer Information</h2>

            <div class="space-y-2 text-sm">
                <p><span class="font-medium">Name:</span> {{ $order->user->name }}</p>
                <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
                <p><span class="font-medium">Phone:</span> {{ $order->user->phone_number ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Shipping Address</h2>

            <div class="text-sm text-gray-700 space-y-1">
                <p>{{ $order->address->street_address }}</p>
                <p>{{ $order->address->city }}, {{ $order->address->district }}</p>
                <p>{{ $order->address->building }}</p>
                <p>{{ $order->address->apartment }}</p>
            </div>
        </div>

    </div>


    <!-- Order Items -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="font-semibold text-gray-800">Order Items</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Product</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Quantity</th>
                        <th class="px-6 py-3">Subtotal</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="px-6 py-4">
                            {{ $item->product->name }}
                        </td>
                        <td class="px-6 py-4">
                            ${{ number_format($item->product->price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 font-medium">
                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Order Summary -->
    <div class="bg-white shadow rounded-lg p-6 md:w-1/2 ml-auto">

        <h2 class="font-semibold text-gray-800 mb-4">Order Summary</h2>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Shipping</span>
                <span>${{ number_format($order->shipping, 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Tax</span>
                <span>
                    $0,00
                    {{-- {{ number_format($order->tax, 2) }} --}}
                </span>
            </div>

            <div class="flex justify-between font-bold text-base border-t pt-3">
                <span>Total</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="mt-4">
            @php
            if($order->status === 'completed') {
                $status_color='bg-green-100 text-green-700';
                } elseif ($order->status === 'cancelled') {
                    $status_color='bg-red-100 text-red-700';
                    } else {
                        $status_color='bg-yellow-100 text-yellow-700';
                        }
                        @endphp
            <span class="px-3 py-1 rounded-full text-xs
                {{ $status_color }}">
                Payment: {{ ucfirst($order->status) }}
            </span>
        </div>

    </div>

</div>


<!-- AJAX Status Update -->
<script>
document.getElementById('statusSelect').addEventListener('change', function () {

    fetch(`/admin/orders/{{ $order->id }}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: this.value })
    })
    .then(res => res.json())
    .then(data => {
         console.log('Status updated');
         window.location.reload();
    })
    .catch(err => console.error('Error updating status:', err));

});
</script>

</x-admin>