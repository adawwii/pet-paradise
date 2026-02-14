<x-layout>
<div class="min-h-screen flex items-center justify-center bg-orange-50 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        
        <!-- Title -->
        <h2 class="text-3xl font-bold text-center text-orange-500 mb-6">
            Create Account
        </h2>

        <!-- Form -->
        <form method="POST" action="/user" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Name
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                    required
                >
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                    required
                >
                @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                    required
                >
                @error('password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none"
                    required
                >
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-orange-500 text-white py-2 rounded-lg font-semibold hover:bg-orange-600 transition"
            >
                Register
            </button>
        </form>

        <!-- Footer -->
        <p class="text-sm text-center text-gray-600 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-orange-500 hover:underline">
                Login
            </a>
        </p>
    </div>
</div>
</x-layout>
