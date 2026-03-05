<x-layout>

<div class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4">

        <h1 class="text-3xl font-bold text-gray-800 mb-8">
            My Orders
        </h1>

        @forelse($orders as $order)

            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">

                <!-- Order Header -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            Order {{ $order->code }}
                        </h2>
                        <p class="text-gray-500 text-sm">
                            Placed on {{ $order->created_at->format('d M Y') }}
                        </p>
                    </div>

                    <div class="mt-4 md:mt-0">
                        @php
                            $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-600',
                                'processing' => 'bg-yellow-100 text-yellow-700',
                                'shipped' => 'bg-blue-100 text-blue-700',
                                'delivered' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                            ];
                        @endphp

                        <span class="px-4 py-1 text-sm font-semibold rounded-full 
                            {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="divide-y">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between py-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->product->image_url ? asset('storage/').$item->product->image_url : asset('images/noImage.png') }}"
                                     class="w-16 h-16 rounded-lg object-cover">

                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{$item->product ? $item->product->name : 'Product Deleted'}}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                         {{$item->product ? 'Qty: ' . $item->quantity : 'N/A'}}
                                    </p>
                                </div>
                            </div>

                            <p class="font-semibold text-gray-700">
                                {{$item->product ? '$'.number_format($item->product->price * $item->quantity, 2) : 'N/A'}}
                            </p>
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="mt-6 border-t pt-6 flex flex-col md:flex-row md:justify-between">

                    <div>
                        <h3 class="font-semibold text-gray-800 mb-2">
                            Shipping Address
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $order->address->street_address }}<br>
                            {{ $order->city }}, {{ $order->address->district }}<br>
                            Building {{ $order->address->building }},
                            Apt {{ $order->address->apartment }}<br>
                            {{-- ZIP: {{ $order->zip }} --}}
                        </p>
                    </div>

                    <div class="mt-6 md:mt-0 text-right">
                        <p class="text-gray-600 text-sm">Total</p>
                        <p class="text-xl font-bold text-gray-800">
                            ${{ number_format($order->total, 2) }}
                        </p>
                    </div>

                </div>

            </div>

        @empty
            <div class="bg-white rounded-2xl shadow p-10 text-center">
                <h2 class="text-xl font-semibold text-gray-700">
                    No Orders Yet
                </h2>
                <p class="text-gray-500 mt-2">
                    Looks like you haven't placed any orders.
                </p>
                <a href="{{ route('shop') }}"
                   class="mt-6 inline-block bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
                    Start Shopping
                </a>
            </div>
        @endforelse

    </div>
</div>
</x-layout>
