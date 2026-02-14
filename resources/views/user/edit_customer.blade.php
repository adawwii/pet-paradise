<x-layout>
<div class="max-w-3xl mx-auto mt-10 bg-white shadow rounded-lg p-6">

  <!-- Page Title -->
  <h2 class="text-2xl font-semibold text-gray-800 mb-6">
    Edit Profile
  </h2>

  <form method="POST" action="/user/profile/edit/{{ auth()->id() }}">

    @csrf 
    @method('PUT') 

    <!-- Name -->
    <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Name
      </label>
      <input
        type="text"
        name="name"
        value="{{ auth()->user()->name }}"
        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="Your name"
      />
      @error('name')
      <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
      @enderror
    </div>

    <!-- Email -->
    <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Email
      </label>
      <input
        type="email"
        name="email"
        value="{{ auth()->user()->email }}"
        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="Your email"
      />
      @error('email')
      <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
      @enderror
    </div>

    <!-- Phone -->
    {{-- <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Phone
      </label>
      <input
        type="text"
        name="phone"
        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="+20 100 000 0000"
      />
    </div> --}}

    <!-- Address -->
    {{-- <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Address
      </label>
      <textarea
        name="address"
        rows="3"
        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="Your address"
      ></textarea>
    </div> --}}

    <!-- Password -->
    <div class="mb-5">
      <label class="block text-sm font-medium text-gray-700 mb-1">
        New Password
      </label>
      <input
        type="password"
        name="password"
        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="Leave blank to keep current password"
      />
      @error('password')
      <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
      @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-1">
        Confirm Password
      </label>
      <input
        type="password"
        name="password_confirmation"
        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        placeholder="Confirm new password"
      />
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3">
      <a
        href="{{ route('profile') }}"
        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
      >
        Cancel
      </a>

      <button
        type="submit"
        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
      >
        Save Changes
      </button>
    </div>
  </form>
</div>
</x-layout>
