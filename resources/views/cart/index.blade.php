<x-layout>
<div class="max-w-6xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    @if($cart && count($cart->cartItems) > 0)

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-6">

                @php
                 $total = 0;
                 $cartItems=$cart->cartItems
                 @endphp

                @foreach($cartItems as $id => $item)

                    @php
                        $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                    @endphp

                    <div class="flex flex-col sm:flex-row items-center bg-white shadow rounded-lg p-4">

                        <!-- Image -->
                        <img src="{{ $item->product->image_url }}"
                             class="w-24 h-24 object-cover rounded-md">

                        <!-- Info -->
                        <div class="flex-1 sm:ml-6 mt-4 sm:mt-0 w-full">

                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-semibold">
                                    {{ $item->product->name }}
                                </h2>

                                <!-- Remove -->
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                        Remove
                                    </button>
                                </form>
                            </div>

                            <p class="text-gray-500 mt-1">
                                ${{ number_format($item->product->price, 2) }}
                            </p>

                            <!-- Quantity -->
                            <form method="POST" action="{{ route('cart.update', $item->id) }}">
                                @csrf
                                @method('PUT')

                                <input type="number"
                                       name="quantity"
                                       value="{{ $item->quantity }}"
                                       min="1"
                                       class="w-20 border rounded-md px-2 py-1 text-center">

                                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded-md text-sm hover:bg-indigo-700 transition">
                                    Update
                                </button>
                            </form>

                            <!-- Subtotal -->
                            <p class="mt-3 font-medium">
                                Subtotal: ${{ number_format($subtotal, 2) }}
                            </p>

                        </div>
                    </div>

                @endforeach

            </div>

            <!-- Summary -->
            <div class="bg-white shadow rounded-lg p-6 h-fit">

                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <div class="flex justify-between mb-4">
                    <span>Shipping</span>
                    <span>Free</span>
                </div>

                <hr class="my-4">

                <div class="flex justify-between text-lg font-bold">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <button class="w-full mt-6 bg-green-600 text-white py-3 rounded-md hover:bg-green-700 transition">
                    Proceed to Checkout
                </button>

                <a href="{{ route('shop') }}"
                   class="block text-center mt-4 text-indigo-600 hover:underline">
                    Continue Shopping
                </a>

            </div>

        </div>

    @else

        <!-- Empty Cart -->
        <div class="text-center py-20">
            <h2 class="text-2xl font-semibold mb-4">Your cart is empty 🐾</h2>
            <a href="{{ route('shop') }}"
               class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition">
                Start Shopping
            </a>
        </div>

    @endif

</div>
</x-layout>