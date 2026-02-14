<x-layout>
<div class="min-h-screen flex items-center justify-center bg-orange-50 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        
        <!-- Title -->
        <h2 class="text-3xl font-bold text-center text-orange-500 mb-6">
            Welcome Back
        </h2>

        <!-- Form -->
        <form method="POST" action="/authenticate" class="space-y-5">
            @csrf

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
                    autofocus
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

            <!-- Remember Me -->
            {{-- <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded text-orange-500">
                    Remember me
                </label>

                <a href="{{ route('password.request') }}"
                   class="text-orange-500 hover:underline">
                    Forgot password?
                </a>
            </div> --}}

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-orange-500 text-white py-2 rounded-lg font-semibold hover:bg-orange-600 transition"
            >
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="text-sm text-center text-gray-600 mt-6">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-orange-500 hover:underline">
                Register
            </a>
        </p>
    </div>
</div>
</x-layout>