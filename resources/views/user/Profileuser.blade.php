<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-white font-roboto">
    <!-- Header -->
    <header class="bg-black p-2 flex justify-between items-center h-16 ">
        <!-- Logo -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('home') }}">
                <img src="http://localhost:8000/images/profile/logo-home.png" alt="Logo" class="h-12 w-15" />
            </a>
        </div>

        <!-- Search Bar -->
        <div class="flex-grow mx-4 mt-5">
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

        <!-- Navigation Links -->
        <nav class="flex items-center space-x-2 sm:space-x-4">
            <a href="/editprofile" class="flex items-center space-x-2 hover:text-green-500">
                <i class="fa-solid fa-gear text-lg"></i>
                <span class="text-sm sm:text-base">Setting</span>
            </a>
        </nav>
    </header>

    <!-- Main -->
    <main class="p-4 ml-auto ml-28 lg:ml-40 xl:ml-60 md:mx-auto max-w-4xl">
        <!-- Profile Info Section -->
        <section
            class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
            <img
                src="https://placehold.co/50x50"
                alt="Profile picture placeholder"
                class="rounded-full h-48 w-48 object-cover" />
            <div>
                <h2 class="text-4xl font-bold mt-10">User Name</h2>
                <p class="text-gray-400">user@example.com</p>
                <p class="mt-2 text-gray-300">
                    This is the user's bio. It can contain a brief introduction or any other details about the user.
                </p>
            </div>
        </section>

        <!-- Playlist Section -->
        <section class="mt-10">
            <h3 class="text-2xl font-bold">Your Playlist</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                <div class="flex items-center space-x-4">
                    <img
                        src="https://placehold.co/50x50"
                        alt="Track 1 image placeholder"
                        class="h-16 w-16 object-cover" />
                    <div>
                        <p class="font-bold">Playlist 1</p>
                        <p class="text-gray-400">Artist 1</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <img
                        src="https://placehold.co/50x50"
                        alt="Track 2 image placeholder"
                        class="h-16 w-16 object-cover" />
                    <div>
                        <p class="font-bold">Playlist 2</p>
                        <p class="text-gray-400">Artist 2</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Artists Section -->
        <section class="mt-10">
            <h3 class="text-2xl font-bold">Nghệ sĩ</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
                <div class="flex flex-col items-center">
                    <img
                        src="https://placehold.co/50x50"
                        alt="Artist 1 image placeholder"
                        class="rounded-full h-24 w-24 object-cover" />
                    <p class="mt-2">HIEUTHUHAI</p>
                </div>
                <div class="flex flex-col items-center">
                    <img
                        src="https://placehold.co/50x50"
                        alt="Artist 2 image placeholder"
                        class="rounded-full h-24 w-24 object-cover" />
                    <p class="mt-2">SƠN TÙNG MTP</p>
                </div>
            </div>
        </section>

        <!-- Uploaded Songs Section -->
        <section class="mt-10">
            <h2 class="text-3xl font-bold">Các bài hát được tải lên</h2>
            <ul class="mt-6 space-y-4">
                <li class="flex items-center space-x-6">
                    <img
                        src="https://storage.googleapis.com/a1aa/image/Vh5OymOlhzrpGNSyzTrQJDAuYen3bRBW76631QVNglJuQ87JA.jpg"
                        alt="Album cover"
                        class="w-16 h-16 object-cover" />
                    <div>
                        <p class="text-lg text-white">
                            BÍCH THƯỢNG QUAN x VẠN SỰ TÙY DUYÊN - Đức Tư Remix
                        </p>
                        <p class="text-gray-500 text-base">Đức Tư Remix • 12 lượt phát</p>
                    </div>
                </li>
                <!-- Add more song items here -->
            </ul>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 p-4 mt-10 text-center">
        <p class="text-gray-500">© 2024 Nulltifly Website</p>
    </footer>
</body>


</html>