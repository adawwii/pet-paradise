@section('title','Add Employee')
<x-admin>
<div class="bg-gray-100 flex items-center justify-center ">

<div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

    <h2 class="text-2xl font-bold text-center mb-6">Employee Registration</h2>

    <form action="{{ route('admin.employee.store') }}"  method="POST" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Full Name</label>
            <input 
                type="text"
                placeholder="Enter your name"
                name="name"
                value="{{ old('name') }}"
                class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                required
            >
        </div>
         @error('name')
        <p class="text-red-500 text-sm ">{{ $message }}</p>
        @enderror

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Email</label>
            <input 
                type="email"
                placeholder="Enter your email"
                name="email"
                value="{{ old('email') }}"
                class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                required
            >
        </div>
         @error('email')
        <p class="text-red-500 text-sm ">{{ $message }}</p>
        @enderror

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Password</label>
            <input 
                type="password"
                id="password"
                placeholder="Enter password"
                name="password"
                class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                required
            >
        </div>
         @error('password')
        <p class="text-red-500 text-sm ">{{ $message }}</p>
        @enderror

        <!-- Confirm Password -->
        <div>
            <label class="block text-sm font-medium text-gray-600">Confirm Password</label>
            <input 
                type="password"
                id="confirmPassword"
                placeholder="Confirm password"
                name="password_confirmation"
                class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                required
            >
        </div>
        

        <!-- Register Button -->
        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
        >
            Register
        </button>

       

    </form>

</div>
</div>

</x-admin>