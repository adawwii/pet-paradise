<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pet Paradise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> 
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
@include('components.flash')

    <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-8">
        <!-- Logo -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Pet Paradise Admin</h1>
            <p class="text-gray-500 mt-1">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <form action="{{ route('admin.authenticate') }}" method="POST" class="space-y-5">
            @csrf
            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                       @error('email')
                          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                           
                       @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Remember Me -->
            {{-- <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 rounded">
                    <span class="text-gray-700 text-sm">Remember Me</span>
                </label>
                <a href="#" class="text-blue-600 text-sm hover:underline">Forgot Password?</a>
            </div> --}}

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Sign In
            </button>
        </form>

        <!-- Footer -->
        <p class="text-gray-400 text-sm text-center mt-6">
            &copy; 2026 Pet Paradise. All rights reserved.
        </p>
    </div>

</body>
</html>