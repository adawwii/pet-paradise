<x-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">

  <!-- Page Title -->
  <div class="mb-10 text-center">
    <h1 class="text-4xl font-bold text-gray-800">Shop by Category</h1>
    <p class="text-gray-500 mt-2">Find products by your pet’s needs</p>
  </div>

  <!-- Categories Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

    <!-- Category Card -->
    @foreach ($categories as $category)
        
    <a href="{{route('shop')}}/?tags={{ $category->name }}" class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">

      <!-- Image -->
      <div class="h-40 bg-gray-100 overflow-hidden">
        <img
          src="{{ asset('images/noImage.png') }}"
          alt="Category"
          class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
        >
      </div>

      <!-- Content -->
      <div class="p-5 text-center">
        <h2 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h2>

        <!-- Rating -->
        @php
        $rating = round($category->products->avg('reviews_avg_rating') ?? 0);
        @endphp

{{-- <div class="flex gap-1 text-yellow-400"> --}}
    @for ($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
    @endfor
{{-- </div> --}}


        <!-- Meta -->
        <p class="text-sm text-gray-500">
          {{ $category->products_count }} Products
        </p>
      </div>

    </a>
    @endforeach

    <!-- End Card -->

  </div>

</div>

</x-layout>