@section('title','Product Details')
<x-admin>
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Back Button -->
    <a href="{{ route('admin.products') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg shadow-sm hover:bg-gray-50 transition w-fit">
        ← Back
    </a>

    <!-- Product Card -->
    <div class="bg-white rounded-xl shadow-lg p-6 grid md:grid-cols-2 gap-8">

        <!-- Product Image -->
        <div>
            <img src="{{ $product->image_url ? asset('storage/'.$product->image_url) : asset('images/noImage.png') }}"
                 class="rounded-xl w-full h-96 object-cover">
        </div>

        <!-- Product Info -->
        <div class="flex flex-col justify-between">

            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    {{ $product->name }}
                </h1>

                <p class="text-gray-500 mb-4">
                    Category: <span class="font-medium text-gray-700">{{ $product->category->name }}</span>
                </p>

                <p class="text-2xl font-semibold text-indigo-600 mb-4">
                    ${{ number_format($product->price, 2) }}
                </p>

                <p class="text-gray-600 mb-6">
                    {{ $product->description }}
                </p>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Stock</p>
                        <p class="font-semibold text-gray-800">{{ $product->stock }} Units</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Created At</p>
                        <p class="font-semibold text-gray-800">{{ $product->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Status Toggle -->
            <div class="mt-6 flex items-center justify-between">

                <div class="flex items-center gap-3">
                    <span class="text-gray-700 font-medium">Status:</span>

                    <!-- Toggle -->
                    @php
                        if($product->is_active) {
                            $toggleStyle="bg-green-500";
                            $circleStyle="";
                            $textStyle="text-green-600";
                            $textContent="Active";
                        }
                        else {
                            $toggleStyle="bg-gray-400";
                            $circleStyle="translate-x-7";
                            $textStyle="text-red-600";
                            $textContent="Inactive";
                        }
                    @endphp
                    <form id="updateForm-{{ $product->id }}" action="{{ route('admin.product.toggle', $product->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                    <button type="button" onclick="openUpdateModal({{ $product->id }})"
                            class="relative w-14 h-7 {{ $toggleStyle }} rounded-full transition">
                        <span 
                              class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition {{ $circleStyle }}"></span>
                    </button>
                    </form>

                    <span 
                          class="{{ $textStyle }} font-semibold">
                        {{ $textContent }}
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('admin.product.edit', $product->id) }}"
                       class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        Edit
                    </a>
                    <form id="deleteForm-{{ $product->id }}" action="{{ route('admin.product.delete', $product->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                    <button type="button" onclick="openDeleteModal({{ $product->id }})"
                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                        Delete
                    </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">

        <h2 class="text-xl font-bold mb-6">Customer Reviews <span class="text-gray-500">({{ $product->reviews_count <= 0 ? 'No reviews yet' : $product->reviews_count . ' reviews' }})</span>
            <span>{{ $product->reviews_avg_rating ? number_format($product->reviews_avg_rating, 1) . '/5' : '' }}</span>
        </h2>

        <div class="space-y-6">

            <!-- Review Item -->
            @foreach ($reviews as $review )
            <div class="border-b pb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">{{ $review->user->name }} <span class="text-xs text-red-600">{{ $review->user->deleted_at ? '(Deleted User)' : '' }}</span></h3>
                    <div class="flex mb-2">
                     @for ($i = 1; $i <= 5; $i++)
                       <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                     @endfor
                    </div>
                </div>
                <p class="text-gray-600">
                    {{ $review->comment }}
                </p>
            </div>
            @endforeach
<div class="mt-8">
  {{ $reviews->links() }}
</div>
        </div>

    </div>

</div>

<!-- Delete Modal -->
@include('partials._delete_modal')
<!-- Confirm Modal -->
@include('partials._confirm_modal')

<script>
    let currentDeleteId = null;
    let currentToggleId = null;

    function openDeleteModal(id) {
        currentDeleteId = 'deleteForm-' + id;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function proccedDelete() {
        document.getElementById(currentDeleteId).submit();
    }
    //toggle product status
    function openUpdateModal(id) {
        currentToggleId = 'updateForm-' + id;
        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeUpdateModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function proccedUpdate() {
        document.getElementById(currentToggleId).submit();
    }
</script>

</x-admin>