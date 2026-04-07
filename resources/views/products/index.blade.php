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
      @php
        if($category->name=='Cats'){
          $categoryImage='https://www.vets4pets.com/siteassets/species/cat/kitten/tiny-kitten-in-sunlight.jpg';

        }elseif ($category->name=='Dogs') {
          $categoryImage='https://cdn.mos.cms.futurecdn.net/BwL2586BtvBPywasXXtzwA-1000-80.jpeg';

        }elseif ($category->name=='Fish') {
          $categoryImage='https://t4.ftcdn.net/jpg/02/97/20/37/360_F_297203796_C8dL0cnQEsQ7Z3NuWR4cBpvBj09vgmPj.jpg';

        }elseif ($category->name=='Birds') {
          $categoryImage='https://d2zp5xs5cp8zlg.cloudfront.net/image-52583-800.jpg';
        }
      @endphp
      <a href="/shop/?tags={{ $category->name }}">
      <div class="category-card">
        <img src="{{ $categoryImage }}" alt="{{ $category->name }}">
        <h3>{{ $category->name }}</h3>
      </div>
      </a>
      @endforeach
      
    </div>
  </section>
</x-layout>

