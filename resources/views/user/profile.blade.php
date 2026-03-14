<x-layout>

<div class="max-w-5xl mx-auto px-4 py-10">

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-5">
                
                <!-- Avatar -->
                {{-- <div class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center text-3xl font-bold text-orange-500">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div> --}}
                <div class="flex flex-col items-center gap-4">
           <!-- Profile Image -->
                <div class="relative">
                  <form action="/user/profile/image/{{ auth()->user()->id }}" enctype="multipart/form-data" method="POST">
                      @csrf
                      @method('PATCH')
                      @if (auth()->user()->image)
                      
                      <img
                      id="previewImage"
                    src="{{ auth()->user()->image ? asset('storage/'.auth()->user()->image) : asset('images/noImage.png') }}"
                    alt="Profile Photo"
                    class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 shadow"
                  />
                  @else
                  <div id="imageReplace" class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center text-3xl font-bold text-orange-500">
                     {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                      </div>

                     <img
                      id="previewImage"
                      src="{{ auth()->user()->image ? asset('storage/'.auth()->user()->image) : asset('images/noImage.png') }}"
                      class="hidden w-32 h-32 rounded-full object-cover mb-4 border"
                       alt="Profile Photo"
                      />
                      @endif

                  <!-- Edit icon -->
                  <label
                    for="profile_photo"
                    class="absolute bottom-1 right-1 bg-indigo-400 text-white p-2 rounded-full cursor-pointer               hover:bg-indigo-600"
                  >
                    ✏️
                  </label>
    
                </div>

                <!-- Hidden file input -->
                <input
                  type="file"
                  id="profile_photo"
                  name="profile_photo"
                  class="hidden"
                  accept="image/*"
                  onchange="previewProfileImage(event)"
                />
                     <!-- Action Buttons -->
  <div id="photoActions" class="hidden mt-4
   {{-- flex  --}}
   gap-3">
    <button
      type="submit"
      class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
    >
      Save
    </button>

    <button
      type="button"
      onclick="cancelPhotoChange()"
      class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
    >
      Cancel
    </button>
  </div>
                <p class="text-sm text-gray-500">Click the pencil to change photo</p>
                </form>
              </div>


                <!-- User Info -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-gray-500">
                        {{ auth()->user()->email }}
                    </p>
                    <span class="inline-block mt-2 px-3 py-1 text-sm {{ auth()->user()->email_verified_at ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}  rounded-full">
                       {{ auth()->user()->email_verified_at ? 'Verified Member' : 'Unverified Member' }} 
                    </span>
                </div>
            </div>

            <!-- Edit Button -->
           <div class="flex flex-col sm:flex-row gap-3">

    <a href="{{ route('editCustomer') }}"
       class="inline-flex items-center justify-center px-5 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
        Edit Profile
    </a>

    <a href="{{ route('orders.customer') }}"
       class="inline-flex items-center justify-center px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
        My Orders
    </a>

</div>
            
        </div>

        <!-- Divider -->
        <hr class="my-8">

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            <div class="bg-orange-50 rounded-lg p-5">
                <h3 class="text-xl font-bold text-orange-500">
                    {{ auth()->user()->reviews()->count() }}
                </h3>
                <p class="text-gray-600 text-sm">Reviews</p>
            </div>

            <div class="bg-orange-50 rounded-lg p-5">
                <h3 class="text-xl font-bold text-orange-500">
                    {{ auth()->user()->orders()->count() ?? 0 }}
                </h3>
                <p class="text-gray-600 text-sm">Orders</p>
            </div>

            <div class="bg-orange-50 rounded-lg p-5">
                <h3 class="text-xl font-bold text-orange-500">
                    {{ auth()->user()->created_at->format('M Y') }}
                </h3>
                <p class="text-gray-600 text-sm">Joined</p>
            </div>
        </div>

    </div>
</div>
</x-layout>
