<x-layout>
@include('partials._hero');
  <section>
    <h2 class="section-title">Featured Products</h2>
    <div class="products">
        @foreach ($topProducts as $product)
            
        <div class="product-card">
            <img src="{{ asset('images/noImage.png') }}" alt="Dog Food">
            <h3>{{ $product->name }}</h3>
            <button>Add to Cart</button>
        </div>
        @endforeach
    </div>
  </section>

  <section>
    <h2 class="section-title">Categories</h2>
    <div class="categories">
      @foreach ($topCategories as $category)
      <div class="category-card">
        <img src="{{ asset('images/noImage.png') }}" alt="Dogs">
        <h3>{{ $category->name }}</h3>
      </div>
      @endforeach
      
    </div>
  </section>
</x-layout>

