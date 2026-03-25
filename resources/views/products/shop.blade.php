<x-layout>
<section class="px-6 py-16">

  <h2 class="text-center text-3xl font-bold text-orange-500 mb-10">
    Shop Products
  </h2>
@include('partials._search')
@include('partials._sort')
  <!-- Products Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

    <!-- Product Card -->
 @foreach($products as $product)
<div class="bg-white rounded-xl shadow-md overflow-hidden hover:-translate-y-1 transition">

    <img
      src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset("images/noImage.png") }}"
      class="w-full h-48 object-cover"
      alt="{{ $product->name }}"
    />

    <div class="p-4 text-center">

      <!-- Product name -->
      <h3 class="text-lg font-semibold mb-1">
        <a href="/shop/{{ $product->id }}">{{ $product->name }}</a>
      </h3>

      <!-- Category -->
      <p class="text-sm text-gray-500 mb-2">
          <a href="/shop/?tags={{ $product->category->name }}">
            {{ $product->category->name }}
        </a>
      </p>

      <!-- Rating -->
      <div class="flex justify-center items-center gap-1 mb-2">
        <span class="text-yellow-400 text-lg">★</span>
        <span class="font-semibold">
          {{ number_format($product->reviews_avg_rating ?? 0, 1) }}
        </span>
        <span class="text-gray-400 text-sm">
          ({{ $product->reviews_count ?? 0 }})
        </span>
      </div>

      <!-- Price -->
      <p class="text-orange-500 font-bold mb-3">
        ${{ $product->price }}
      </p>
      <form action="{{ route('cart.add',$product) }}" method="post">
        @csrf
        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-400 transition">
          Add to Cart
        </button>
      </form>

    </div>
</div>
@endforeach


  </div>

  <!-- Pagination -->
  <div class="mt-12 flex justify-center [&_a]:text-orange-500 [span]:px-3 [&_a]:px-4 [&_a]:mx-0 [&_span]:mx-0">
    {{ $products->links() }}
    {{-- <nav class="flex gap-2">
      <a href="#" class="px-4 py-2 border rounded-lg text-orange-500 hover:bg-orange-100">1</a>
      <span class="px-4 py-2 bg-orange-500 text-white rounded-lg">2</span>
      <a href="#" class="px-4 py-2 border rounded-lg text-orange-500 hover:bg-orange-100">3</a>
    </nav> --}}
  </div>

</section>

</x-layout>
