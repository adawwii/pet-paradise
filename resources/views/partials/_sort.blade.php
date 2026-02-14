<div class="max-w-xl mx-auto mb-8 flex flex-col sm:flex-row sm:justify-between gap-3">

    <!-- Sorting Dropdown -->
    <form method="GET" class="flex gap-2 items-center">
        <!-- Keep search & category in hidden inputs -->
        {{-- <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="category" value="{{ request('category') }}"> --}}

        <label for="sort" class="font-semibold">Sort by:</label>
        <select name="sort" id="sort" onchange="this.form.submit()"
                class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400">
            <option value="">Default</option>
            <option value="rate" {{ request('sort') == 'rate' ? 'selected' : '' }}>Highest Rated</option>
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
        </select>
    </form>

    <!-- Reset Button -->
    <a href="{{ route('shop') }}"
       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
       Reset Filters
    </a>

</div>
