@section('title','Orders Management')
<x-admin>
<div class="max-w-7xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        {{-- <h1 class="text-2xl font-bold text-gray-800">Orders Management</h1> --}}
        <a 
        href="{{ route('admin.orders.export') }}"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
           Export CSV
        </a>
        
        <div class="flex flex-col md:flex-row gap-3">
            <!-- Search -->
            <form >
                <input type="text" name="search" id="searchInput"
                placeholder="Search by Order ID or Customer..."
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </form>

            <!-- Status Filter -->
            <form >
            <select
            id="statusFilter"
            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            name="status"
            onchange="this.form.submit()"
            >
                <option value="">All Status</option>
                <option value="pending"{{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="processing"{{ request('status')=='processing'?'selected':'' }}>Processing</option>
                <option value="shipped"{{ request('status')=='shipped'?'selected':'' }}>Shipped</option>
                <option value="completed"{{ request('status')=='completed'?'selected':'' }}>Completed</option>
                <option value="cancelled"{{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
            </form>

        </div>
       
    </div>


    <!-- Orders Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Address</th>
                        <th class="px-6 py-3">Payment Status</th>
                        <th class="px-6 py-3">Payment Date</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                @forelse($orders as $order)
                    <tr class="order-row hover:bg-gray-50">

                        <!-- Order ID -->
                        <td class="px-6 py-4 font-medium">
                            {{ $order->code }}
                        </td>

                        <!-- Customer -->
                        <td class="px-6 py-4">
                            {{ $order->user->name ?? 'N/A' }} <span class="text-xs text-red-600">{{ $order->user->deleted_at ? '(Deleted User)' : '' }}</span>
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4">
                            ${{ number_format($order->total, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->address->building }},
                            {{ $order->address->street_address }},
                            {{ $order->address->district }},
                            {{ $order->address->city }},
                            Appartment {{ $order->address->apartment }}
                        </td>
                        <!-- Payment Status -->
                        <td class="px-6 py-4">
                            @php
                                if($order->paid == true) {
                                    $status_color='bg-green-100 text-green-700';
                                    $payment_status='PAID';
                                } else {
                                    $status_color='bg-red-100 text-red-700';
                                    $payment_status='NOT PAID';
                                }
                                
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $status_color }}">
                                {{ ucfirst($payment_status) }}
                            </span>
                        </td>
                        <!-- Payment Date -->
                        <td class="px-6 py-4">
                            {{ $order->paid_at ? $order->paid_at->format('M d, Y') : 'N/A' }}   
                        </td>

                        <!-- Order Status Dropdown -->
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="post">
                                @csrf
                                @method('PUT')
                            <select
                                data-id="{{ $order->id }}"
                                class="status-badge status-select border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500">

                                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Processing</option>
                                <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                                <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Completed</option>
                                <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
                            </select>
                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-right">
                            <a 
                            href="{{ route('admin.orders.single', $order->id) }}"
                               class="text-blue-600 hover:underline text-sm">
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


    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>

</div>


<script>
    //search and filter functionality
//     const searchInput = document.getElementById("searchInput");
// const statusFilter = document.getElementById("statusFilter");
// const rows = document.querySelectorAll(".order-row");

// function filterOrders() {
//     const searchValue = searchInput.value.toLowerCase();
//     const statusValue = statusFilter.value;

//     rows.forEach(row => {
//         const orderId = row.children[0].textContent.toLowerCase();
//         const customer = row.children[1].textContent.toLowerCase();
//         const status = row.querySelector(".status-badge").textContent;

//         const matchesSearch = orderId.includes(searchValue) || customer.includes(searchValue);
//         const matchesStatus = statusValue === "" || status === statusValue;

//         if (matchesSearch && matchesStatus) {
//             row.style.display = "";
//         } else {
//             row.style.display = "none";
//         }
//     });
// }

// searchInput.addEventListener("input", filterOrders);
// statusFilter.addEventListener("change", filterOrders);

//  AJAX Status Update 
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function () {

        const orderId = this.dataset.id;
        const status = this.value;

        fetch(`/admin/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
                window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });

    });
});
</script>
</x-admin>