<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> 
</head>

<body class="bg-gray-100">
@include('components.flash')

<div class="flex">

    <!-- Fixed Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-screen bg-gray-900 text-white transition-all duration-300 flex flex-col w-64">

        <!-- Logo & Toggle -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <span id="logoText" class="text-xl font-bold">Pet Paradise</span>
            <button id="toggleSidebar" class="text-gray-400 hover:text-white">
                ☰
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2 text-sm overflow-y-auto">

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>🏠</span>
                <span class="link-text">Dashboard</span>
            </a>

            <a href="{{ route('admin.orders') }}"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>📦</span>
                <span class="link-text">Orders</span>
            </a>

            <a href="{{ route('admin.products') }}"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>🐾</span>
                <span class="link-text">Products</span>
            </a>

            <!-- Add Product -->
            <a href="#"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>➕</span>
                <span class="link-text">Add Product</span>
            </a>

            <a href="{{ route('admin.customers') }}"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>👤</span>
                <span class="link-text">Users</span>
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-gray-700">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
            <button type="submit"
               class="flex items-center w-full gap-3 p-2 rounded hover:bg-red-600 bg-red-500">
                <span>🚪</span>
                <span class="link-text">Logout</span>
            </button>
            </form>
        </div>

    </aside>


    <!-- Main Content -->
    <div id="mainContent" class="flex-1 ml-64 transition-all duration-300 min-h-screen flex flex-col">

        <!-- Topbar -->
        <header class="bg-white shadow p-4 flex justify-between">
            <h1 class="font-semibold text-lg">@yield('title')</h1>
            <div>Admin</div>
        </header>

        <main class="p-6">
            {{ $slot }}
        </main>

    </div>

</div>


<script>
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const logoText = document.getElementById("logoText");
    const linkTexts = document.querySelectorAll(".link-text");
    const mainContent = document.getElementById("mainContent");

    if (localStorage.getItem("sidebarCollapsed") === "true") {
        collapseSidebar();
    }

    toggleBtn.addEventListener("click", () => {
        
        if (sidebar.classList.contains("w-64")) {
            collapseSidebar();
        } else {
            expandSidebar();
        }
    });

    function collapseSidebar() {
        sidebar.classList.remove("w-64");
        sidebar.classList.add("w-20");

        mainContent.classList.remove("ml-64");
        mainContent.classList.add("ml-20");

        logoText.style.display = "none";
        linkTexts.forEach(el => el.style.display = "none");

        localStorage.setItem("sidebarCollapsed", "true");
    }

    function expandSidebar() {
        sidebar.classList.remove("w-20");
        sidebar.classList.add("w-64");

        mainContent.classList.remove("ml-20");
        mainContent.classList.add("ml-64");

        logoText.style.display = "block";
        linkTexts.forEach(el => el.style.display = "inline");

        localStorage.setItem("sidebarCollapsed", "false");
    }
    
</script>

</body>
</html>