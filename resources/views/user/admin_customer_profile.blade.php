@section('title','Customer Profile')
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
                <h1 class="text-2xl font-bold text-gray-800">Customer Details</h1>
                <p class="text-gray-500 text-sm">Manage customer account</p>
            </div>
        </div>

        <!-- Delete / Restore Button -->
        @if ($customer->deleted_at)
         <form id="updateForm-{{ $customer->id }}" action="{{ route('admin.customer.restore', $customer->id) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="button" onclick="openUpdateModal({{ $customer->id }})" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                Restore Account
            </button>
         </form>
        @else
         <form id="deleteForm-{{ $customer->id }}" action="{{ route('admin.customer.delete', $customer->id) }}"      method="POST" class="inline">
             @csrf
             @method('DELETE')
             <button type="button" onclick="openDeleteModal({{ $customer->id }})" class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                 Delete Account
                </button>
         </form>

        @endif

    </div>

    <!-- Customer Info Card -->
    <div class="bg-white rounded-xl shadow p-6 grid md:grid-cols-3 gap-6">

        <!-- Profile -->
        <div class="flex flex-col items-center text-center border-r md:pr-6">
            <img src="{{ $customer->image ? asset('storage/'.$customer->image) : asset('images/noImage.png') }}"
                 class="w-24 h-24 rounded-full mb-4">

            <h2 class="text-xl font-semibold text-gray-800">
                {{ $customer->name }}
            </h2>

            <p class="text-gray-500 text-sm mb-3">
                #CUS-{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}
            </p>

            <!-- Verification Status -->
            <span class="px-3 py-1 text-xs font-semibold rounded-full block {{ $customer->email_verified_at ? ' text-green-700 bg-green-100' : 'bg-yellow-100 text-yellow-700' }}">
                {{ $customer->email_verified_at ? 'Verfied('.$customer->email_verified_at->format('M d, Y').')' : 'Unverified' }}
            </span>

            <!-- Account Status -->
            <span id="accountStatus"
                  class="mt-3 px-3 py-1 text-xs font-semibold rounded-full {{ !$customer->deleted_at ? ' text-green-700 bg-green-100' : 'bg-red-100 text-red-700' }}">
                {{ $customer->deleted_at ? 'Deleted' : 'Active' }}
            </span>
        </div>

        <!-- Personal Info -->
        <div class="md:col-span-2 grid sm:grid-cols-2 gap-6">

            <div>
                <p class="text-gray-500 text-sm">Email</p>
                <p class="font-medium text-gray-800">{{ $customer->email ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Phone</p>
                <p class="font-medium text-gray-800">{{ $customer->phone_number ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Joined</p>
                <p class="font-medium text-gray-800">{{ $customer->created_at->format('M d, Y') ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Total Orders</p>
                <p class="font-medium text-gray-800">{{ $orders->count() ?? 0 }} Orders</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Total Spent</p>
                <p class="font-medium text-gray-800">${{ $orders->sum('total') ?? 0 }}</p>
            </div>

        </div>

    </div>

    <!-- Orders Section -->
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-lg font-bold mb-4">Orders</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Order ID</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Total</th>
                    <th class="px-4 py-3 text-left">Payment Status</th>
                    <th class="px-4 py-3 text-left">Order Status</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($orders as $order)
                    @php
                                if($order->paid == true) {
                                    $status_color='bg-green-100 text-green-700';
                                    $payment_status='PAID';
                                } else {
                                    $status_color='bg-red-100 text-red-700';
                                    $payment_status='NOT PAID';
                                }
                                
                            @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $order->code }}</td>
                    <td class="px-4 py-3">{{ $order->created_at->format('D m, Y') }}</td>
                    <td class="px-4 py-3">${{ $order->total }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 capitalize text-xs {{ $status_color }} rounded-full">
                            {{ $payment_status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 capitalize text-xs bg-green-100 text-green-700 rounded-full">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.orders.single',$order->id) }}" class="text-indigo-600 hover:underline">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
<div class="mt-6">
        {{ $orders->links() }}
    </div>
    <!-- Reviews Section -->
    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-lg font-bold mb-4">Customer Reviews</h2>

        <div class="space-y-4">
            @forelse ($reviews as $review)
                
            
            <div class="border-b pb-3">
                <div class="flex justify-between items-center mb-1">
                    <p class="font-medium text-gray-800">{{ $review->product->name }}</p>
                    <span class="block">
                         @for ($i = 1; $i <= 5; $i++)
                         <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                         @endfor
                    </span>
                </div>
                <p class="text-gray-600 text-sm">
                    {{ $review->comment }}
                </p>
            </div>

            @empty
                 <div>
                        <p  class="px-6 py-6 text-center text-gray-500">
                            No Reveiws found.
                        </p>
                    <div>
            @endforelse

        </div>

    </div>
    <div class="mt-6">
            {{ $reviews->links() }}
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