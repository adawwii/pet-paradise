@section('title','Reviews Managment')
<x-admin>
<div class="p-6 max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

        <h1 class="text-2xl font-bold mb-6">⭐ Reviews Management</h1>
        <!-- Search -->
        <div class="flex gap-4 ">

            <form>
                @if (request('reviewStatus') == true)
                <input type="hidden" name="reviewStatus" value="{{ request('reviewStatus') }}">
                @endif
                <input type="text" name="search" id="searchInput"
                placeholder="Type review,item or Cust..."
                value="{{ request('search') }}"
                class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </form>
              @php
                    if(request('reviewStatus') == true) {
                        if(request('reviewStatus') == 'approved') {
                            $approved_selected=true;
                            $pending_selected=false;
                            $all_selected=false;
                            $rejected_selected=false;
                        } else if(request('reviewStatus') == 'all') {
                            $all_selected=true;
                            $approved_selected=false;
                            $pending_selected=false;
                            $rejected_selected=false;
                        } else if(request('reviewStatus') == 'rejected') {
                            $rejected_selected=true;
                            $pending_selected=false;
                            $all_selected=false;
                            $approved_selected=false;
                        } else if(request('reviewStatus') == 'pending') {
                            $pending_selected=true;
                            $all_selected=false;
                            $approved_selected=false;
                            $rejected_selected=false;
                        }
                    }
                    else {
                        $all_selected=true;
                        $pending_selected=false;
                        $approved_selected=false;
                        $rejected_selected=false;
                    }
                @endphp
                <form >
             <select name="reviewStatus" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option {{ $all_selected ? 'selected' : '' }} value="all">All Reviews</option>
                <option {{ $pending_selected ? 'selected' : '' }} value="pending">Pending</option>
                <option {{ $approved_selected ? 'selected' : '' }} value="approved">Approved</option>
                <option {{ $rejected_selected ? 'selected' : '' }} value="rejected">rejected</option>
            </select>
                </form>
        </div>

    </div>
    
  <!-- Review Card -->
  @forelse ($reviews as $review)
  <div class="bg-white rounded-xl shadow p-6 mb-6 review-card" data-status="pending">

    <!-- Header -->
    <div class="flex justify-between items-start mb-3">
      <div>
        <h2 class="font-semibold text-lg"><a href="{{ route('admin.customer.profile',$review->user->id) }}">{{ $review->user->name }}</a><span class="text-xs text-red-600">{{ $review->user->deleted_at ? '(Deleted User)' : '' }}</span></h2>
        <p class="text-sm text-gray-500">Product: <a href="{{ route('admin.products.details',$review->product->id) }}">{{ $review->product->name }}</a> <span class="text-xs text-red-600">{{ $review->product->deleted_at ? '(Deleted Product)' : '' }}</span></p>
      </div>
      @php
          if($review->status == 'approved') {
            $statusColor='bg-green-100 text-green-700';
          } else if($review->status == 'rejected') {
            $statusColor='bg-red-100 text-red-700';
          } else {
            $statusColor='bg-yellow-100 text-yellow-700';
          }
      @endphp
      <span class="status-badge {{ $statusColor }}  px-3 py-1 rounded-full text-sm">
        {{ $review->status }}
      </span>
    </div>

    <!-- Rating -->
    <div class="text-yellow-500 mb-3 text-lg">
      <span class="block">
                         @for ($i = 1; $i <= 5; $i++)
                         <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                         @endfor
                    </span>
    </div>

    <!-- COMMENT (Now Full Width & Bigger) -->
    <p class="text-gray-700 leading-relaxed text-base mb-4">
     {{ $review->comment }}
    </p>

    <!-- Actions -->
    <div class="flex gap-3">
        @if ($review->status == 'approved' || $review->status == 'rejected')

        <form method="POST" id="updateForm-{{ $review->id }}" class="inline" action="{{ route('admin.review.status',$review->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="{{ $review->status == 'approved' ? 'rejected' : 'approved' }}">
            <button type="button" onclick="openUpdateModal({{ $review->id }})"  class="{{ $review->status == 'approved' ? 'bg-orange-500' : 'bg-green-500' }} text-white px-4 py-2 rounded-lg text-sm">
                {{ $review->status == 'approved' ? 'Reject' : 'Approve' }}
            </button>
        </form>

        @else

        <form method="POST" id="updateForm-{{ $review->id .'1' }}" class="inline" action="{{ route('admin.review.status',$review->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="approved">
            <button type="button" onclick="openUpdateModal({{ $review->id .'1' }})" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm">
                Approve
            </button>
        </form>
        <form method="POST" id="updateForm-{{ $review->id .'2' }}" class="inline" action="{{ route('admin.review.status',$review->id) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="rejected">
            <button type="button" onclick="openUpdateModal({{ $review->id .'2' }})"  class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm">
            Reject
        </button>
    </form>
                            
        @endif
        <form method="POST" id="deleteForm-{{ $review->id }}" class="inline" action="{{ route('admin.review.delete',$review->id) }}">
            @csrf
            @method('DELETE')
            <button type="button" onclick="openDeleteModal({{ $review->id }})" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm">
                Delete
            </button>
        </form>
    </div>

  </div>
  @empty
  <div>
      <p  class="px-6 py-6 text-center text-gray-500">
          No Reveiws found.
        </p>
        <div>
  @endforelse
  <div class="mt-6">
        {{ $reviews->links() }}
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