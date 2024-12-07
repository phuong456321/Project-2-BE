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
    </script>
</head>

<body class="bg-gray-900">
    <!-- Header -->
    <header class="bg-black p-2 flex justify-between items-center border-b border-gray-700 fixed top-0 left-0 w-full z-10">
        <!-- Logo -->
        <div class="flex items-center">
            <a class="no-hover" href="{{ route('home') }}">
                <img alt="Logo" height="100" src="http://localhost:8000/images/profile/logo-home.png" width="100" />
            </a>
        </div>

        <!-- Search Bar -->
        <div class="flex-grow mx-4">
            <form class="relative max-w-lg mx-auto">
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
        <div class="text-white mr-10 hover:text-green-500">
            <i class="fa-solid fa-user"></i>
            <a href="/Profileuser"> Profile </a>
        </div>
    </header>


    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-1/5 bg-black text-white fixed top-[50px] left-0 h-[calc(100vh-50px)] border-r border-gray-700">
            <div class="text-2xl font-bold p-6 mt-10 "> <!-- Adjusted margin-top to create space for logo -->
                Cài đặt
            </div>
            <ul class="space-y-4 p-6">
                <li>
                    <a href="/editprofile" class="block py-2 px-4 rounded hover:bg-gray-700 active">Chỉnh sửa hồ sơ</a>
                </li>
                <li>
                    <a href="/uploadedsong" class="block py-2 px-4 rounded hover:bg-gray-700 active">Danh sách bài hát</a>
                </li>
                <li>
                    <a href="/layoutsetting" class="block py-2 px-4 rounded hover:bg-gray-700 active">Cài đặt khác</a>
                </li>
            </ul>
            <!-- Logout Button at the bottom left -->
            <div class="absolute bottom-6 left-6 w-100 ">
                <a href="{{ route('logout') }}" class=" text-base font-semibold block py-2 px-4 bg-red-600 text-white rounded text-center hover:bg-red-700">
                    <i class="fa-sharp fa-solid fa-right-from-bracket inline-block "></i>
                    Logout
                </a>
            </div>
    </div>

    <!-- Main Content -->
    <div class="w-4/5 ml-[20%] bg-gray-900 p-6 h-[calc(100vh-50px)] overflow-y-auto mt-[50px]">
        @yield('content')
    </div>
    </div>
</body>


</html>