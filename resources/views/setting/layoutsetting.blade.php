<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        content.classList.add('ml-70'); // Dịch nội dung chính sang phải
                    } else {
                        sidebar.classList.add('-translate-x-full'); // Ẩn sidebar
                        content.classList.remove('ml-64'); // Đưa nội dung về trạng thái bình thường
                    }
                });
            }
        });
    </script>
</head>

<body class="bg-gray-900">
    <!-- Header -->
    <header class="bg-black p-2 flex justify-between items-center border-b border-gray-700 fixed top-0 left-0 w-full z-20 h-16">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img alt="Logo" height="50" src="http://localhost:8000/images/profile/logo-home.png" width="60" />
            </a>
        </div>

        <!-- Search Bar -->
        <div class="flex-grow mx-4 mt-1">
            <form class="relative max-w-lg mx-auto w-full sm:w-1/4 md:w-1/2 lg:max-w-lg">
              <input
                    type="text"
                    placeholder="Tìm kiếm..."
                    class="w-full bg-black text-white py-2 px-4 rounded-full border border-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500" />
                <button
                    type="submit"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Profile -->
        <div class="flex items-center space-x-4 mr-5">
            <button id="menuToggle" class="sm:hidden text-white p-2 border border-gray-700 rounded focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="hidden sm:block text-white hover:text-green-500">
                <i class="fa-solid fa-user"></i>
                <a href="/Profileuser"> Profile </a>
            </div>
        </div>
    </header>


    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-[64px] left-0 h-[calc(100vh-50px)] w-64 bg-black text-white border-r border-gray-700 transform -translate-x-full sm:translate-x-0 transition-transform duration-300 ease-in-out z-20">
        <div class="text-2xl font-bold p-6">Cài đặt</div>
        <ul class="space-y-4 p-6">
            <li><a href="/editprofile" class="block py-2 px-4 rounded hover:bg-gray-700">Chỉnh sửa hồ sơ</a></li>
            <li><a href="{{ route('uploadedsong') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Danh sách bài hát</a></li>
            <li><a href="{{ route('profile', Auth::user()->id) }}" class="block py-2 px-4 rounded hover:bg-gray-700">Quay lại trang hồ sơ</a></li>
        </ul>
        <div class="absolute bottom-8 left-6 w-100">
            <a href="{{ route('logout') }}" class="text-base font-semibold block py-2 px-4 bg-red-600 text-white rounded text-center hover:bg-red-700">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>
    <!-- Nội dung chính -->
    <main id="content" class="ml-0 sm:ml-64 bg-gray-900 p-6 mt-[50px] transition-all duration-300 ease-in-out">
        @yield('content')
    </main>
</body>


</html>