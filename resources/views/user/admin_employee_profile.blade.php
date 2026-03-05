@section('title','Employee Profile')
<x-admin>
<div class="p-6 space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">

        <div class="flex items-center gap-4">
            <a href="javascript:history.back()"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg shadow-sm hover:bg-gray-50 transition w-fit">
                ← Back
            </a>

            <div>
                <h1 class="text-2xl font-bold text-gray-800">Employee Details</h1>
                <p class="text-gray-500 text-sm">Manage Employee account</p>
            </div>
        </div>

        <!-- Delete / Restore Button -->
        @if ($employee->deleted_at)
         <form id="updateForm-{{ $employee->id }}" action="{{ route('admin.employee.restore', $employee->id) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="button" onclick="openUpdateModal({{ $employee->id }})" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                Restore Employee
            </button>
         </form>
        @else
         <form id="deleteForm-{{ $employee->id }}" action="{{ route('admin.employee.delete', $employee->id) }}"      method="POST" class="inline">
             @csrf
             @method('DELETE')
             <button type="button" onclick="openDeleteModal({{ $employee->id }})" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                 Remove Employee
                </button>
         </form>

        @endif

    </div>

    <!-- Customer Info Card -->
    <div class="bg-white rounded-xl shadow p-6 grid md:grid-cols-3 gap-6">

        <!-- Profile -->
        <div class="flex flex-col items-center text-center border-r md:pr-6">
            <img src="{{ $employee->image ? asset('storage/'.$employee->image) : asset('images/noImage.png') }}"
                 class="w-24 h-24 rounded-full mb-4">

            <h2 class="text-xl font-semibold text-gray-800">
                {{ $employee->name }}
            </h2>

            <p class="text-gray-500 text-sm mb-3">
                #EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}
            </p>

            <!-- Verification Status -->
            <span class="px-3 py-1 text-xs font-semibold rounded-full block {{ $employee->email_verified_at ? ' text-green-700 bg-green-100' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $employee->email_verified_at ? 'Verfied('.$employee->email_verified_at->format('M d, Y').')' : 'Unverified' }}
            </span>

            <!-- Account Status -->
            <span id="accountStatus"
                  class="mt-3 px-3 py-1 text-xs font-semibold rounded-full {{ !$employee->deleted_at ? ' text-green-700 bg-green-100' : 'bg-red-100 text-red-700' }}">
                {{ $employee->deleted_at ? 'Deleted' : 'Active' }}
            </span>
        </div>

        <!-- Personal Info -->
        <div class="md:col-span-2 grid sm:grid-cols-2 gap-6">

            <div>
                <p class="text-gray-500 text-sm">Email</p>
                <p class="font-medium text-gray-800">{{ $employee->email ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Phone</p>
                <p class="font-medium text-gray-800">{{ $employee->phone_number ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Joined</p>
                <p class="font-medium text-gray-800">{{ $employee->created_at->format('M d, Y') ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Products Added</p>
                <p class="font-medium text-gray-800">{{ $products->count() ?? 0 }} Product</p>
            </div>

        </div>

    </div>

    <!-- Products Section -->
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-lg font-bold mb-4">Products</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Product Name</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Price</th>
                    <th class="px-4 py-3 text-left">Stock</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($products as $product)
                   
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ $product->created_at->format('D m, Y') }}</td>
                    <td class="px-4 py-3">${{ $product->price }}</td>
                    <td class="px-4 py-3">
                            {{ $product->stock }} Piece
                    </td>
                    
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.products.details',$product->id) }}" class="text-indigo-600 hover:underline">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                            No Products found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
<div class="mt-6">
        {{ $products->links() }}
    </div>
 
</div>
</div>
@include('partials._confirm_modal')
@include('partials._delete_modal')
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