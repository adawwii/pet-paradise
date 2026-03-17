@section('title','Employee Managment')
<x-admin>

<div class="max-w-7xl mx-auto p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        
        <div class="flex flex-col sm:flex-row gap-3">
        <h1 class="text-2xl font-bold text-gray-800">Employees Managment</h1>
  <a href="{{ route('admin.employee.register') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Add New Employee
            </a>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">

            <!-- Search -->
            <form >
                <input type="text" name="search" id="searchInput"
                placeholder="Search Employee..."
                value="{{ request('search') }}"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </form>

            <form>
               
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
                <option {{ $available_selected ? 'selected' : '' }} value="available">Available Employee</option>
                <option {{ $trashed_selected ? 'selected' : '' }} value="trashed">Deleted Employee</option>
                <option {{ $all_selected ? 'selected' : '' }} value="all">All Employee</option>
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
                    <th class="px-6 py-3">Joined</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
                </thead>

                <tbody class="divide-y">

                @foreach ($employees as $employee)
                    
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $employee->image ? asset('storage/' . $employee->image) : asset('images/noImage.png') }}"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-gray-800">{{ $employee->name }}</p>
                            <p class="text-xs text-gray-500">#EMP-{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $employee->email }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $employee->phone_number ?? 'N/A' }}
                    </td>
                    
                    <td class="px-6 py-4 text-gray-600">
                        {{ $employee->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $employee->email_verified_at ? ' text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $employee->email_verified_at ? $employee->email_verified_at->format('M d, Y') : 'Unverified' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">

                        <a href="{{ route('admin.employee.profile' , $employee->id) }}"
                           class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            View
                        </a>

                        @if ($employee->deleted_at)
                        <form id="updateForm-{{ $employee->id }}" action="{{ route('admin.employee.restore', $employee->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                        <button type="button" onclick="openUpdateModal({{ $employee->id }})" class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded hover:bg-green-200">
                            Restore
                        </button>
                        </form>
                         <form id="deleteForm-{{ $employee->id }}" action="{{ route('admin.employee.forceDelete', $employee->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                        <button type="button" onclick="openDeleteModal({{ $employee->id }})" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
                             Delete Permanently 
                        </button>
                        </form>
                        @else
                        <form id="deleteForm-{{ $employee->id }}" action="{{ route('admin.employee.delete', $employee->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                        <button type="button" onclick="openDeleteModal({{ $employee->id }})" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200">
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
            {{ $employees->links() }}

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