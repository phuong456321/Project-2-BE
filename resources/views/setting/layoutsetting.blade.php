<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo-home.webp') }}" type="image/webp">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('.sidebar a');
            const mainContent = document.getElementById('main-content');

            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const url = e.target.getAttribute('href');

                    // Fetch nội dung từ server (AJAX)
                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            mainContent.innerHTML = html;
                        })
                        .catch(err => console.error('Lỗi:', err));
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Lấy các thành phần cần thiết
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            // Kiểm tra nếu các phần tử tồn tại
            if (menuToggle && sidebar && content) {
                // Trạng thái sidebar ban đầu
                let isSidebarOpen = false;

                // Sự kiện click trên nút menu
                menuToggle.addEventListener('click', () => {
                    isSidebarOpen = !isSidebarOpen; // Thay đổi trạng thái sidebar

                    // Xử lý các lớp CSS dựa trên trạng thái
                    if (isSidebarOpen) {
                        sidebar.classList.remove('-translate-x-full'); // Hiển thị sidebar
                    } else {
                        sidebar.classList.add('-translate-x-full'); // Ẩn sidebar
                    }
                });
            }
        });
    </script>

</head>

<body class="bg-gray-900">
    <!-- Header -->
    <header class="bg-black p-2 flex justify-between items-center border-b border-gray-700 fixed top-0 left-0 w-full h-16">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img alt="Logo" height="50" src="{{ asset('images/logo-home.webp') }}" width="60" />
            </a>
        </div>

        <!-- Profile -->
        <div class="flex items-center space-x-4 mr-5">
            <button id="menuToggle" class="sm:hidden text-white p-2 border border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="hidden sm:block text-white hover:text-green-500">
                <i class="fa-solid fa-user"></i>
                <a href="/profile/{{Auth::user()->id}}"> Profile </a>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-[64px] left-0 h-[calc(100vh-50px)] w-64 bg-black text-white border-r border-gray-700 transform -translate-x-full sm:translate-x-0 transition-transform duration-300 ease-in-out z-20">
        <div class="text-2xl font-bold p-6">Settings</div>
        <ul class="space-y-4 p-6">
            <li><a href="/editprofile" class="block py-2 px-4 rounded hover:bg-gray-700">Edit Profile</a></li>
            <li><a href="{{ route('uploadedsong') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Song List</a></li>
            <li><a href="{{ route('profile', Auth::user()->id) }}" class="block py-2 px-4 rounded hover:bg-gray-700">Back to Profile</a></li>
        </ul>
        <div class="absolute bottom-8 left-6 w-100">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="text-base font-semibold py-2 px-4 bg-red-600 text-white rounded text-center hover:bg-red-700 w-full">
            <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
        </button>
    </form>
</div>

    </aside>

    <!-- Main Content -->
    <main id="content" class="ml-0 sm:ml-64 bg-gray-900 p-6 mt-[50px] transition-all duration-300 ease-in-out">
        @yield('content')
    </main>
</body>

</html>