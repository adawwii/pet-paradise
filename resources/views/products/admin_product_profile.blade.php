@section('title','Product Details')
<x-admin>
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Back Button -->
    <a href="javascript:history.back()"
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
                    <button id="statusToggle"
                            class="relative w-14 h-7 bg-green-500 rounded-full transition">
                        <span id="toggleCircle"
                              class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition"></span>
                    </button>

                    <span id="statusText"
                          class="text-green-600 font-semibold">
                        Active
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('admin.product.edit', $product->id) }}"
                       class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        Edit
                    </a>

                    <button onclick="openDeleteModal()"
                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                        Delete
                    </button>
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
            <div class="border-b pb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">Ahmed Ali</h3>
                    <span class="text-yellow-500">★★★★★</span>
                </div>
                <p class="text-gray-600">
                    Excellent quality! My dog loves it.
                </p>
            </div>

            <div class="border-b pb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">Sara Mohamed</h3>
                    <span class="text-yellow-500">★★★★☆</span>
                </div>
                <p class="text-gray-600">
                    Very good product but delivery was slightly delayed.
                </p>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800">Omar Hassan</h3>
                    <span class="text-yellow-500">★★★★★</span>
                </div>
                <p class="text-gray-600">
                    Highly recommended. Will buy again!
                </p>
            </div>

        </div>

    </div>

</div>

<!-- Delete Modal -->
<div id="deleteModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-600 mb-6">
            Are you sure you want to delete this product?
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Cancel
            </button>

            <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
    const toggle = document.getElementById('statusToggle');
    const circle = document.getElementById('toggleCircle');
    const text = document.getElementById('statusText');

    let isActive = true;

    toggle.addEventListener('click', () => {
        isActive = !isActive;

        if (isActive) {
            toggle.classList.remove('bg-gray-400');
            toggle.classList.add('bg-green-500');
            circle.classList.remove('translate-x-7');
            text.textContent = "Active";
            text.classList.remove('text-red-600');
            text.classList.add('text-green-600');
        } else {
            toggle.classList.remove('bg-green-500');
            toggle.classList.add('bg-gray-400');
            circle.classList.add('translate-x-7');
            text.textContent = "Inactive";
            text.classList.remove('text-green-600');
            text.classList.add('text-red-600');
        }
    });

    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

</x-admin>