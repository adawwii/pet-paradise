@section('title','Customers Managment')
<x-admin>

<div class="max-w-7xl mx-auto p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Customers</h1>

        <div class="flex flex-col sm:flex-row gap-3">

            <!-- Search -->
            <input type="text"
                   placeholder="Search customer..."
                   class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">

            <!-- Filter -->
            <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option>All Status</option>
                <option>Active</option>
                <option>Blocked</option>
            </select>

        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Orders</th>
                    <th class="px-6 py-3">Joined</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y">

                @foreach ($customers as $customer)
                    
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $customer->image ? asset('storage/' . $customer->image) : asset('images/noImage.png') }}"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                            <p class="text-xs text-gray-500">#CUS-{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $customer->email }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $customer->phone_number ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        {{ $customer->orders_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $customer->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">

                        <a href="#"
                           class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            View
                        </a>

                        <button class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                            Block
                        </button>

                        <button class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                            Delete
                        </button>

                    </td>
                </tr>
                @endforeach
                <!-- Blocked Example -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="https://i.pravatar.cc/41"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-gray-800">Sara Mohamed</p>
                            <p class="text-xs text-gray-500">#CUS-002</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        sara@email.com
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        +20 102 987 6543
                    </td>
                    <td class="px-6 py-4 font-semibold">
                        5
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        Feb 02, 2026
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                            Blocked
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">

                        <a href="#"
                           class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            View
                        </a>

                        <button class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded hover:bg-green-200">
                            Unblock
                        </button>

                        <button class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                            Delete
                        </button>

                    </td>
                </tr>

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t flex items-center justify-end">
            {{ $customers->links() }}

    </div>

</div>

</x-admin>