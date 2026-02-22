
<x-admin>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

        <!-- Top Navbar -->
        {{-- <header class="bg-white shadow p-4 flex justify-between items-center">
            <button id="menuBtn" class="md:hidden text-gray-700">
                ☰
            </button>

            <h1 class="text-xl font-semibold">Admin Dashboard</h1>

            <div>
                <span class="text-gray-600">Admin</span>
            </div>
        </header> --}}

        <!-- Content -->
        <main class="p-6">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-gray-500">Total Orders</h3>
                    <p class="text-3xl font-bold mt-2">{{ $ordersCount }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-gray-500">Revenue</h3>
                    <p class="text-3xl font-bold mt-2">${{ $revenue }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-gray-500">Users</h3>
                    <p class="text-3xl font-bold mt-2">{{ $usersCount }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-gray-500">Products</h3>
                    <p class="text-3xl font-bold mt-2">{{ $productsCount }}</p>
                </div>

            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2">Order ID</th>
                                <th class="px-4 py-2">Customer</th>
                                <th class="px-4 py-2">Amount</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $order->code }}</td>
                                <td class="px-4 py-2">{{ $order->user->name }}</td>
                                <td class="px-4 py-2">${{ $order->total }}</td>
                                <td class="px-4 py-2">
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $order->created_at->format('d/M/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</x-admin>
