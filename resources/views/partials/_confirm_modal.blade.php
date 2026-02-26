<div id="confirmModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
        <h2 class="text-lg font-semibold mb-4">Confirm Update</h2>
        <p class="text-gray-600 mb-6">
            Are you sure you want to {{ $product->is_active ? 'Deactivate' : 'Activate' }} this product?
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeUpdateModal()"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Cancel
            </button>

            <button  onclick="proccedUpdate()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                {{ $product->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </div>
    </div>
</div>