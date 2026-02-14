<x-layout>
<section class="max-w-7xl mx-auto px-6 py-16">
<a
  href="{{ route('shop') }}"
  class="inline-flex items-center gap-2 mb-6 text-sm font-semibold text-orange-500 hover:text-orange-400 transition"
>
  ← Back
</a>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

    <!-- Product Image -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
      <img
        src="{{ asset('images/noImage.png') }}"
        alt="{{ $product->name }}"
        class="w-full h-[420px] object-cover"
      />
    </div>

    <!-- Product Info -->
    <div>

      <!-- Category -->
      <p class="text-sm text-orange-500 font-semibold mb-2">
        {{ $product->category->name }}
      </p>

      <!-- Product Name -->
      <h1 class="text-3xl font-bold mb-3">
        {{ $product->name }}
      </h1>

      <!-- Rating -->
      <div class="flex items-center gap-2 mb-4">
        @php $rating = round($product->reviews_avg_rating ?? 0); @endphp

        <div class="flex">
          @for ($i = 1; $i <= 5; $i++)
            <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
          @endfor
        </div>

        <span class="text-gray-500 text-sm">
          ({{ $product->reviews_count }} reviews)
        </span>
      </div>

      <!-- Price -->
      <p class="text-2xl text-orange-500 font-bold mb-4">
        ${{ $product->price }}
      </p>

      <!-- Description -->
      <p class="text-gray-600 mb-6">
        {{ $product->description }}
      </p>

      <!-- Add to Cart -->
      <form method="POST" action="#">
        @csrf
        <div class="flex items-center gap-4 mb-6">
          <input
            type="number"
            min="1"
            value="1"
            class="w-20 px-3 py-2 border rounded-lg"
          />

          <button
            class="px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-400 transition"
          >
            Add to Cart
          </button>
        </div>
      </form>

      <!-- Extra Info -->
      <ul class="text-sm text-gray-600 space-y-1">
        <li>✔ In stock</li>
        <li>✔ Fast delivery</li>
        <li>✔ Secure payment</li>
      </ul>

    </div>
  </div>

  <!-- Reviews Section -->
  <div class="mt-16">

    <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>

    <!-- Reviews List -->
    <div class="space-y-6 mb-10">

      @foreach($reviews as $review)
      <div class="bg-white p-6 rounded-xl shadow-md">

        <div class="flex justify-between items-center mb-2">
          <p class="font-semibold">{{ $review->user->name }}</p>
          <span class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
        </div>

        <div class="flex mb-2">
          @for ($i = 1; $i <= 5; $i++)
            <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
          @endfor
        </div>

        <p class="text-gray-600">
          {{ $review->comment }}
        </p>
      </div>
      @endforeach

    </div>
<div class="mt-8">
  {{ $reviews->links() }}
</div>
    <!-- Add Review -->
    @auth
    <div class="bg-white p-6 rounded-xl shadow-md max-w-xl">

      <h3 class="text-xl font-semibold mb-4">Add a Review</h3>

      <form method="POST" action="/addReview/?product={{ $product->id }}">
        @csrf

        <label class="block mb-2 font-semibold">Rating</label>
        <select
          name="rating"
          class="w-full mb-4 px-4 py-2 border rounded-lg"
        >
          <option value="5">★★★★★ (5)</option>
          <option value="4">★★★★☆ (4)</option>
          <option value="3">★★★☆☆ (3)</option>
          <option value="2">★★☆☆☆ (2)</option>
          <option value="1">★☆☆☆☆ (1)</option>
        </select>

        <label class="block mb-2 font-semibold">Comment</label>
        <textarea
          name="comment"
          rows="4"
          class="w-full px-4 py-2 border rounded-lg mb-4"
        ></textarea>

        <button
          class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-400 transition"
        >
          Submit Review
        </button>

      </form>
    </div>
    @else
      <p class="text-gray-500">
        Please <a href="/login" class="text-orange-500 underline">login</a> to write a review.
      </p>
    @endauth

  </div>

</section>
</x-layout>
