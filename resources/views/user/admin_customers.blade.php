@section('title','Customers Managment')
<x-admin>

<div class="max-w-7xl mx-auto p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Customers</h1>

        <div class="flex flex-col sm:flex-row gap-3">

            <!-- Search -->
            <form >
                @if (request('account_type') == true)
                <input type="hidden" name="account_type" value="{{ request('account_type') }}">
                @endif
                @if (request('status') == true)
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" id="searchInput"
                placeholder="Search Customer..."
                value="{{ request('search') }}"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </form>

            <!-- Filter -->
            <form>
                @if (request('account_type') == true)
                <input type="hidden" name="account_type" value="{{ request('account_type') }}">
                @endif
                @php
                    if(request('status') == true) {
                        if(request('status') == 'unverified') {
                            $unverified_selected=true;
                            $verified_selected=false;
                            $all_selected=false;
                        } else if(request('status') == 'all') {
                            $all_selected=true;
                            $unverified_selected=false;
                            $verified_selected=false;
                        } else if(request('status') == 'verified') {
                            $verified_selected=true;
                            $all_selected=false;
                            $unverified_selected=false;
                        }
                    }
                    else {
                        $all_selected=true;
                        $verified_selected=false;
                        $unverified_selected=false;
                    }
                @endphp
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option {{ $all_selected ? 'selected' : '' }} value="all">All Status</option>
                <option {{ $verified_selected ? 'selected' : '' }} value="verified">Verified</option>
                <option {{ $unverified_selected ? 'selected' : '' }} value="unverified">Unverified</option>
            </select>
            </form>
            {{-- filteration depending on customer's account availability --}}
            <form>
                @if (request('status') == true)
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @php
                    if(request('account_type') == true) {
                        if(request('account_type') == 'trashed') {
                            $trashed_selected=true;
                            $available_selected=false;
                            $all_selected=false;
                        } else if(request('account_type') == 'all') {
                            $all_selected=true;
                            $trashed_selected=false;
                            $available_selected=false;
                        } else if(request('account_type') == 'available') {
                            $available_selected=true;
                            $all_selected=false;
                            $trashed_selected=false;
                        }
                    }
                    else {
                        $available_selected=true;
                        $all_selected=false;
                        $trashed_selected=false;
                    }
                @endphp
            <select name="account_type" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option {{ $available_selected ? 'selected' : '' }} value="available">Available customers</option>
                <option {{ $trashed_selected ? 'selected' : '' }} value="trashed">Deleted customers</option>
                <option {{ $all_selected ? 'selected' : '' }} value="all">All customers</option>
            </select>
            </form>

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
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $customer->email_verified_at ? ' text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $customer->email_verified_at ? $customer->email_verified_at->format('M d, Y') : 'Unverified' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">

                        <a href="{{ route('admin.customer.profile' , $customer->id) }}"
                           class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            View
                        </a>

                        @if ($customer->deleted_at)
                        <form id="updateForm-{{ $customer->id }}" action="{{ route('admin.customer.restore', $customer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                        <button type="button" onclick="openUpdateModal({{ $customer->id }})" class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded hover:bg-green-200">
                            Restore
                        </button>
                        </form>
                        @else
                        <form id="deleteForm-{{ $customer->id }}" action="{{ route('admin.customer.delete', $customer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                        <button type="button" onclick="openDeleteModal({{ $customer->id }})" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                            Delete
                        </button>
                        </form>

                        @endif

                    </td>
                </tr>
                @endforeach
                

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t flex items-center justify-end">
            {{ $customers->links() }}

    </div>

</div>
@include('partials._delete_modal')
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