@section('title', 'Add New Product')
<x-admin>

    {{-- <div class="p-6">
    <a href="javascript:history.back()"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border rounded-lg shadow-sm hover:bg-gray-50 transition w-fit">
        
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>
</div> --}}
<div class="max-w-4xl mx-auto p-6">
    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create a new product for your store</h1>
        {{-- <p class="text-gray-500 text-sm">Create a new product for your store</p> --}}
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-lg rounded-xl p-6">

        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!-- Product Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Product Name
                </label>
                <input type="text"
                name="name"
                placeholder="Enter product name"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <!-- -- Validation Error -->
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <!-- Category & Price Row -->
            <div class="grid md:grid-cols-2 gap-6">

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Category
                    </label>
                    <select name="category" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option
                         selected 
                         disabled 
                         value="">
                         Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <!-- -- Validation Error -->
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Price ($)
                    </label>
                    <input type="number"
                           name="price"
                           step="0.01"
                           placeholder="0.00"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                           <!-- -- Validation Error -->
                               @error('price')
                                   <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                               @enderror
                </div>

            </div>

            <!-- Stock & Status -->
            <div class="grid md:grid-cols-2 gap-6">

                <!-- Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stock Quantity
                    </label>
                    <input type="number"
                           name="stock"
                           placeholder="Enter stock quantity"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                           <!-- -- Validation Error -->
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Product Status
                    </label>

                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="1" checked
                                   class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-gray-700">Active</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_active" value="0"
                                   class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-gray-700">Inactive</span>
                        </label>
                        <!-- -- Validation Error -->
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    </div>
                </div>

            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description
                </label>
                
                <textarea rows="4"
                          name="description"
                          placeholder="Write product description..."
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
                          <!-- -- Validation Error -->
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Product Image
                </label>

                <div class="flex items-center gap-6">

                    <!-- Image Preview -->
                    <div class="w-32 h-32 border rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                        <img id="imagePreview"
                             src="{{ asset('images/noImage.png') }}"
                             class="object-cover w-full h-full">
                    </div>

                    <!-- File Input -->
                    <div>
                        <input type="file"
                               id="imageInput"
                               name="image_url"
                               accept="image/*"
                               class="block text-sm text-gray-600
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:text-sm file:font-semibold
                               file:bg-indigo-50 file:text-indigo-700
                               hover:file:bg-indigo-100">
                        <p class="text-xs text-gray-400 mt-2">
                            PNG, JPG up to 2MB
                        </p>
                    </div>

                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t">

                <a href="#"
                   class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow">
                    Save Product
                </button>

            </div>

        </form>

    </div>

</div>

<!-- Image Preview Script -->
<script>
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.addEventListener('load', function () {
                imagePreview.setAttribute('src', this.result);
            });

            reader.readAsDataURL(file);
        }
    });
</script>
</x-admin>