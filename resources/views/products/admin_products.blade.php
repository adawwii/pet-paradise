
@section('title','Products Managment')
<x-admin>
<div class="p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        {{-- <h1 class="text-2xl font-bold text-gray-800">Products</h1> --}}

        <div class="flex gap-3">
            <form>

                <input
                type="text"
                name="search"
                placeholder="Search product..."
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </form>

            <form action="">
            <button
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                Reset
            </button>
            </form>
            <a href="{{ route('admin.products.add') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Add Product
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Price</th>
                    <th class="px-6 py-3">Stock</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y">

                <!-- Product Row -->
                @foreach ($products as $product )
                     @php
                            if($product->stock >=1) {
                                $stock_condition=$product->stock.' in stock';
                            } else {
                                $stock_condition='Out of stock';
                            }
                            if($product->is_active == true) {
                                $product_condition='Active';
                                $product_Design='bg-green-100 text-green-700';
                            } else {
                                $product_condition='Inactive';
                                $product_Design='bg-gray-100 text-gray-700';
                            }   
                        @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <img src="{{ 
                            // $product->image_url 
                            // ? asset('storage/' . $product->image_url) 
                            // : 
                            asset('images/noImage.png') 
                            }}"
                             class="w-14 h-14 rounded-lg object-cover">
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                     <a href="{{ route('admin.products.details', $product->id) }}">{{ $product->name }}</a>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                     <a href="{{ route('admin.products') }}?tags={{ $product->category->name }}">{{ $product->category->name }}</a>
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        ${{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $stock_condition }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $product_Design }}">
                            {{ $product_condition }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.product.edit', $product->id) }}"
                           class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            Edit
                        </a>
                        <form id="deleteForm-{{ $product->id }}" action="{{ route('admin.product.delete', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal({{ $product->id }})"
                            class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                            Delete
                        </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>

</div>

<!-- Delete Modal -->
@include('partials._delete_modal')

<script>
    let currentDeleteId = null;
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
</script>
</x-admin>
{{-- 
</body>
</html> --}}