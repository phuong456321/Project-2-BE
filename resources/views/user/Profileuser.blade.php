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
    <header class="bg-black p-1 flex justify-between items-center">
        <div class="flex items-center">
            <a class="no-hover" href="{{ route('home') }}">
                <img alt="Logo" height="100" src="http://localhost:8000/images/profile/logo-home.png" width="100" />
            </a>
        </div>
        <div class="flex-grow mx-4 mt-4 mr-20">
        <form class="relative max-w-lg mx-auto">
            <input
                type="text"
                placeholder="Tìm kiếm..."
                class="w-full bg-black text-white py-2 px-4 rounded-full border border-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
            />
            <button
                type="submit"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>
    </div>

        <nav class="flex space-x-4 absolute top-7 right-10">
            <a class="hover:text-green-500" href="/editprofile">
                <i class="fa-duotone fa-solid fa-gear"> </i> Setting </a>
        </nav>
    </header>
    <main class="p-4 ml-32">
        <section
            class="flex flex-col md:flex-row items-center md:items-start md:space-x-6">
            <img
                alt="Profile picture placeholder"
                class="rounded-full h-48 w-48"
                height="200"
                src="https://placehold.co/50x50"
                width="200" />
            <div class="mt-4 md:mt-10">
                <h2 class="text-4xl font-bold">User Name</h2>
                <p class="text-gray-400">user@example.com</p>
                <p class="mt-2 text-gray-300">This is the user's bio. It can contain a brief introduction or any other details about the user.</p>
                <div class="mt-4 flex space-x-4">
                </div>
                
        </section>

        <section class="mt-10">
            <h3 class="text-2xl font-bold">Your playist</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                <div class="flex items-center space-x-4">
                    <img
                        alt="Track 1 image placeholder"
                        class="h-16 w-16"
                        height="100"
                        src="https://placehold.co/50x50"
                        width="100" />
                    <div>
                        <p class="font-bold">playist 1</p>
                        <p class="text-gray-400">Artist 1</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <img
                        alt="Track 2 image placeholder"
                        class="h-16 w-16"
                        height="100"
                        src="https://placehold.co/50x50"
                        width="100" />
                    <div>
                        <p class="font-bold">Playist 2</p>
                        <p class="text-gray-400">Artist 2</p>
                    </div>
                </div>
            </div>
            <h3 class="text-2xl font-bold mt-10">Nghệ sĩ</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
                <div class="flex flex-col items-center">
                    <img
                        alt="Artist 1 image placeholder"
                        class="rounded-full h-24 w-24"
                        height="150"
                        src="https://placehold.co/50x50"
                        width="150" />
                    <p class="mt-2">HIEUTHUHAI</p>
                </div>
                <div class="flex flex-col items-center">
                    <img
                        alt="Artist 2 image placeholder"
                        class="rounded-full h-24 w-24"
                        height="150"
                        src="https://placehold.co/50x50"
                        width="150" />
                    <p class="mt-2">SƠN TÙNG MTP</p>
                </div>
            </div>
        </section>
        <section class="mt-8">
            <h2 class="text-3xl font-bold mt-4">
                Các bài hát được tải lên
            </h2>
            <ul class="mt-6 space-y-4">
                <li class="flex items-center space-x-6">
                    <img
                        alt="Album cover for BÍCH THƯỢNG QUAN x VẠN SỰ TÙY DUYÊN"
                        class="w-16 h-16"
                        height="64"
                        src="https://storage.googleapis.com/a1aa/image/Vh5OymOlhzrpGNSyzTrQJDAuYen3bRBW76631QVNglJuQ87JA.jpg"
                        width="64" />
                    <div class="flex-1">
                        <div class="text-white text-lg">
                            BÍCH THƯỢNG QUAN x VẠN SỰ TÙY DUYÊN - Đức Tư Remix
                        </div>
                        <div class="text-gray-500 text-base">
                            Đức Tư Remix • 12 lượt phát
                        </div>
                    </div>
                </li>

                <!-- Các mục khác cũng được mở rộng tương tự -->
            </ul>
        </section>
    </main>
    <footer class="bg-gray-800 p-4 mt-8">
        <!-- <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <a class="hover:text-green-500" href="#">
                    <i class="fab fa-facebook-f"> </i>
                </a>
                <a class="hover:text-green-500" href="#">
                    <i class="fab fa-twitter"> </i>
                </a>
                <a class="hover:text-green-500" href="#">
                    <i class="fab fa-instagram"> </i>
                </a>
            </div>
        </div> -->
    </footer>
</body>

</html>