<x-layout>
@include('partials._hero');
  <section>
    <h2 class="section-title">Featured Products</h2>
    <div class="products">
        @foreach ($topProducts as $product)
            
        <div class="product-card">
            <img src="{{ asset('images/noImage.png') }}" alt="Dog Food">
            <h3><a href="{{ route('customer.product.profile',$product->id) }}">{{ $product->name }}</a></h3>
            <form action="{{ route('cart.add',$product->id) }}" method="POST">
        @csrf
        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-400 transition">
          Add to Cart
        </button>
      </form>
        </div>
        @endforeach
    </div>
  </section>

  <section>
    <h2 class="section-title">Categories</h2>
    <div class="categories">
      @foreach ($topCategories as $category)
      <a href="/shop/?tags={{ $category->name }}">
      <div class="category-card">
        <img src="{{ asset('images/noImage.png') }}" alt="Dogs">
        <h3>{{ $category->name }}</h3>
      </div>
      </a>
      @endforeach
      
    </div>
  </section>
</x-layout>

