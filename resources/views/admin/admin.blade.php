<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css') <!-- Laravel Mix -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>
    @yield('head')
    <style>
        .sidebar {
            will-change: transform;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">
    <!-- Main Container -->
    <div x-data="{ isOpen: false }" class="flex h-screen">
        <!-- Sidebar -->
        <div :class="isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="sidebar bg-gray-800 w-64 h-full fixed lg:static transform transition-transform duration-300 z-10 flex flex-col">

            <!-- Close Button (Mobile only) -->
            <button @click="isOpen = false" class="text-gray-400 hover:text-white absolute top-4 right-4 lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Sidebar Content -->
            <div class="p-6 font-bold text-lg border-b border-gray-700">
                Admin Management
            </div>

            <!-- Sidebar Menu -->
            <ul class="mt-6 space-y-4 flex-grow">
                <li>
                    <a href="{{ route('admin.songs') }}" class="flex items-center p-4 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        Manage Songs
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.manageUsers') }}" class="flex items-center p-4 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A2.828 2.828 0 0112 15c0 1.657-1.343 3-3 3a2.828 2.828 0 01-2.879-2.196zM12 15v6m0 0h-3m3 0h3m2.879-6a2.828 2.828 0 01-2.879 2.196" />
                        </svg>
                        Manage Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.manageAuthors') }}"
                        class="flex items-center p-4 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18m-9 5h9" />
                        </svg>
                        Manage Authors
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.songApproval') }}" class="flex items-center p-4 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18m-6-6h6m-6 12h6" />
                        </svg>
                        Song Approval
                    </a>
                </li>
            </ul>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-700">
                <button type="button" onclick="openLogoutModal()"
                    class="w-full flex items-center justify-center p-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3V5a3 3 0 013-3h5a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </div>
        </div>
        <!-- Logout Confirmation Modal -->
        <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <h2 class="text-xl font-bold text-white mb-4">Confirm Logout</h2>
                <p class="text-gray-400 mb-6">Are you sure you want to logout?</p>
                <div class="flex justify-end space-x-4">
                    <!-- Cancel Button -->
                    <button onclick="closeLogoutModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
                        Cancel
                    </button>
                    <!-- Confirm Logout -->
                    <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div :class="!isOpen ? 'lg:ml-0' : 'ml-0'"
            class="flex-1 transition-all duration-300 bg-gray-700 overflow-y-auto main-content">
            <!-- Header -->
            <header class="bg-gray-900 p-6 flex items-center lg:hidden">
                <button @click="isOpen = true" class="text-gray-400 hover:text-white focus:outline-none lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <h1 class="ml-4 text-xl font-bold">Dashboard</h1>
            </header>

            <!-- Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<script>
    @yield('script')

    function openLogoutModal() {
        // Hiển thị modal
        document.getElementById('logoutModal').classList.remove('hidden');
    }

    function closeLogoutModal() {
        // Ẩn modal
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>

</html>
