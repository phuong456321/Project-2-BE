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
        <nav class="flex items-center space-x-1 sm:space-x-4">
            <!-- Notification Button -->
            <button
                id="notificationButton"
                class="relative text-white rounded-full px-2 py-2 hover:bg-gray-600 text-sm sm:text-base">
                <i class="fa-solid fa-bell"></i>
            </button>
            <a
                href="/editprofile"
                class="flex items-center space-x-2 hover:text-green-500">
                <i class="fa-solid fa-gear text-lg"></i>
            </a>

            <!-- Popup Notification -->
            <div
                id="notificationPopup"
                class="hidden absolute top-14 right-4 bg-gray-800 text-white p-4 rounded-lg shadow-lg w-[14rem] sm:w-60 md:w-72">
                <p class="font-bold mb-2">Đã đọc tất cả thông báo</p>
                <ul>
                    @foreach($notifications as $notification)
                    <li>
                        <strong>{{ $notification->data['song_name'] }}</strong>: {{ $notification->data['message'] }}
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                    @endforeach
                </ul>
                <button
                    id="closePopup"
                    class="mt-3 text-sm text-gray-300 hover:text-gray-200">
                    Đóng
                </button>
            </div>
        </nav>
    </header>

    <!-- Main -->
    <main class="p-4 ml-auto ml-28 lg:ml-40 xl:ml-60 md:mx-auto max-w-4xl">
        <!-- Profile Info Section -->
        <section
            class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
            <img
                src="{{ url('image/' . $user->avatar_id) }}"
                alt="Profile picture"
                class="rounded-full h-48 w-48 object-cover" />
            <div>
                <h2 class="text-4xl font-bold mt-10">{{ $user->name}}</h2>
                <p class="text-gray-400">{{ $user->email}}</p>
                <p class="mt-2 text-gray-300">{{ $user->author->author_name}}</p>
            </div>
        </section>

        <!-- Playlist Section -->
        <section class="mt-10">
            <h3 class="text-2xl font-bold">Your Playlist</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach ($playlists as $playlist)
                <div class="flex items-center space-x-4">
                    <img
                        src="{{ $playlist->name == 'Liked music' ? 'https://i1.sndcdn.com/artworks-4Lu85Xrs7UjJ4wVq-vuI2zg-t500x500.jpg' : 'http://localhost:8000/images/profile/logo-home.png' }}"
                        alt="Track 1 image placeholder"
                        class="h-16 w-16 object-cover" />
                    <div>
                        <p class="font-bold">{{ $playlist->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Uploaded Songs Section -->
        <section class="mt-10">
            <h2 class="text-3xl font-bold">Các bài hát được tải lên</h2>
            <ul class="mt-6 space-y-4">
                @foreach ($songs as $song)
                <li class="flex items-center space-x-6">
                    <img
                        src="https://storage.googleapis.com/a1aa/image/Vh5OymOlhzrpGNSyzTrQJDAuYen3bRBW76631QVNglJuQ87JA.jpg"
                        alt="Album cover"
                        class="w-16 h-16 object-cover" />
                    <div>
                        <p class="text-lg text-white">
                            {{ $song->song_name }} - {{ $song->author->author_name}}
                        </p>
                        <p class="text-gray-500 text-base">{{ $song->play_count }} lượt nghe - {{ $song->likes }} lượt thích</p>
                    </div>
                </li>
                @endforeach
                <!-- Add more song items here -->
            </ul>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 p-4 mt-10 text-center">
        <p class="text-gray-500">© 2024 Nulltifly Website</p>
    </footer>
    <script>
        const notificationButton = document.getElementById("notificationButton");
        const notificationPopup = document.getElementById("notificationPopup");
        const closePopup = document.getElementById("closePopup");

        notificationButton.addEventListener("click", () => {
            notificationPopup.classList.toggle("hidden");
        });

        closePopup.addEventListener("click", () => {
            notificationPopup.classList.add("hidden");
        });
    </script>

</body>

</html>