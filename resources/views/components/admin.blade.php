<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="bg-gray-900 text-white h-screen transition-all duration-300 flex flex-col w-64">

        <!-- Logo & Toggle -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <span id="logoText" class="text-xl font-bold">Pet Paradise</span>
            <button id="toggleSidebar" class="text-gray-400 hover:text-white">
                ☰
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a 
            {{-- href="{{ route('admin.dashboard') }}" --}}
            href="#"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>🏠</span>
                <span class="link-text">Dashboard</span>
            </a>

            <a 
            {{-- href="{{ route('admin.orders') }}" --}}
            href="#"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>📦</span>
                <span class="link-text">Orders</span>
            </a>

            <a 
            {{-- href="{{ route('admin.products') }}" --}}
            href="#"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>🐶</span>
                <span class="link-text">Products</span>
            </a>

            <a
             {{-- href="{{ route('admin.users') }}" --}}
             href="#"
               class="flex items-center gap-3 p-2 rounded hover:bg-gray-800">
                <span>👤</span>
                <span class="link-text">Users</span>
            </a>

        </nav>

    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

        <!-- Topbar -->
        <header class="bg-white shadow p-4 flex justify-between">
            <h1 class="font-semibold text-lg">@yield('title')</h1>
            <div>Admin</div>
        </header>

        <main class="p-6">
            {{ $slot }}
        </main>

    </div>


<script>
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleSidebar");
    const logoText = document.getElementById("logoText");
    const linkTexts = document.querySelectorAll(".link-text");

    // Load saved state
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

        logoText.style.display = "none";
        linkTexts.forEach(el => el.style.display = "none");

        localStorage.setItem("sidebarCollapsed", "true");
    }

    function expandSidebar() {
        sidebar.classList.remove("w-20");
        sidebar.classList.add("w-64");

        logoText.style.display = "block";
        linkTexts.forEach(el => el.style.display = "inline");

        localStorage.setItem("sidebarCollapsed", "false");
    }
</script>

</body>
</html>