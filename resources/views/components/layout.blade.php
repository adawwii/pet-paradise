<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Paradise - Online Pet Store</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
    }

    body {
      background-color: #fdf6f0;
      color: #333;
    }




    /* nav a {
      color: #fff;
      text-decoration: none;
      margin-left: 25px;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    } */

    .hero {
      background: url('https://images.unsplash.com/photo-1592194996308-7b43878e84a6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w5MTMyMXwwfDF8c2VhcmNofDF8fHBldHxlbnwwfHx8fDE2OTc0Mjg2MzY&ixlib=rb-4.0.3&q=80&w=1080') no-repeat center center/cover;
      height: 400px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
      position: relative;
    }

    .hero::after {
      content: '';
      position: absolute;
      top:0;
      left:0;
      width:100%;
      height:100%;
      background: rgba(0,0,0,0.5);
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero h2 {
      font-size: 48px;
      margin-bottom: 20px;
    }

    .hero a {
      padding: 12px 30px;
      font-size: 18px;
      background-color: #ffb347;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .hero a:hover {
      background-color: #ff9b21;
    }

    section {
      padding: 60px 50px;
    }

    h2.section-title {
      text-align: center;
      margin-bottom: 40px;
      font-size: 32px;
      color: #ff8c42;
    }

    .products, .categories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .product-card, .category-card {
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.3s;
    }

    .product-card img, .category-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .product-card h3, .category-card h3 {
      padding: 15px 10px;
      font-size: 20px;
    }

    .product-card button {
      margin-bottom: 15px;
      padding: 10px 20px;
      border: none;
      background-color: #ff8c42;
      color: #fff;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .product-card button:hover {
      background-color: #ff9b21;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    /* footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 30px;
      margin-top: 50px;
    }

    footer a {
      color: #ffb347;
      text-decoration: none;
    } */
  </style>
</head>
<body>
@include('components.flash')

<header x-data="{ open: false }" class="bg-orange-500 shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        
        <!-- Logo -->
        <a href="/" class="text-white text-2xl font-bold">
            🐾 Pet Paradise
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-6 text-white font-medium">
            <a href="/" class="hover:text-orange-200">Home</a>
            <a href="{{ route('shop') }}" class="hover:text-orange-200">Shop</a>
            <a href="{{ route('category') }}" class="hover:text-orange-200">Categories</a>
            <a href="#" class="hover:text-orange-200">Contact</a>

            @guest
                <a href="{{ route('login') }}"
                   class="px-4 py-2 border border-white rounded hover:bg-white hover:text-orange-500 transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="px-4 py-2 bg-white text-orange-500 rounded hover:bg-orange-100 transition">
                    Register
                </a>
            @endguest

            @auth
                <a href="{{ route('profile') }}"
                   class="px-4 py-2 bg-white text-orange-500 rounded hover:bg-orange-100 transition">
                    Profile
                </a>
                @include('partials.cart_button')
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-left  hover:text-orange-200">
                        Logout
                    </button>
                </form>
            @endauth
        </nav>

        <!-- Mobile Button -->
        <button @click="open = !open" class="md:hidden text-white focus:outline-none">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition @click.outside="open = false"
         class="md:hidden bg-orange-500 border-t border-orange-400">
        <nav class="flex flex-col px-6 py-4 space-y-3 text-white font-medium">
            <a href="/" class="hover:text-orange-200">Home</a>
            <a href="{{ route('shop') }}" class="hover:text-orange-200">Shop</a>
            <a href="{{ route('category') }}" class="hover:text-orange-200">Categories</a>
            <a href="#" class="hover:text-orange-200">Contact</a>

            <hr class="border-orange-400">

            @guest
                <a href="{{ route('login') }}" class="hover:text-orange-200">Login</a>
                <a href="{{ route('register') }}" class="hover:text-orange-200">Register</a>
            @endguest

            @auth
                <a href="{{ route('profile') }}" class="hover:text-orange-200">Profile</a>
                @include('partials.cart_button')

                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-left hover:text-orange-200">
                        Logout
                    </button>
                </form>
            @endauth
        </nav>
    </div>
</header>




 {{ $slot }}

<footer class="bg-gray-900 text-gray-300 text-center py-6">
    <p class="text-sm">
        © 2026 Pet Paradise. All Rights Reserved. |
        <a href="#" class="text-orange-400 hover:text-orange-300 underline">
            Privacy Policy
        </a>
    </p>
</footer>

<script>
  const originalImage = document.getElementById('previewImage').src;

  function previewProfileImage(event) {
    const input = event.target;
    const preview = document.getElementById('previewImage');
    const actions = document.getElementById('photoActions');
    const imageReplace= document.getElementById('imageReplace');

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        actions.classList.remove('hidden');
        imageReplace.classList.add('hidden');
        preview.classList.remove('hidden');
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  function cancelPhotoChange() {
    const input = document.getElementById('profile_photo');
    const preview = document.getElementById('previewImage');
    const actions = document.getElementById('photoActions');
    const imageReplace= document.getElementById('imageReplace');

    input.value = '';
    preview.src = originalImage;
    actions.classList.add('hidden');
    imageReplace.classList.remove('hidden');
    preview.classList.add('hidden');
  }

  
</script>


</body>
</html>
