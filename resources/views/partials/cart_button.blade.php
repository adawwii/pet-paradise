<a href="{{ route('cart.show') }}" 
   class="relative inline-flex items-center p-2 text-gray-700 hover:text-indigo-600 transition">

    <!-- Cart Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" 
         class="h-6 w-6" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke="currentColor">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6h13M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
    </svg>

    <!-- Badge -->
    @php
        $cartCount = auth()->user()->cart ? count(auth()->user()->cart->cartItems) : 0;
    @endphp

    <span id="cart-count"
          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
        {{ $cartCount }}
    </span>

</a>
