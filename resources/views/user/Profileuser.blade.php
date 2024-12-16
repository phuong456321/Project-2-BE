<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .notification-item.unread {
            background-color: #2d3748;
            /* Màu tối hơn */
            border-left: 4px solid #4a90e2;
            /* Viền màu nổi bật */
            padding-left: 8px;
        }
    </style>
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
        {{-- <div class="flex-grow mx-4 mt-5">
            <form class="relative max-w-lg mx-auto w-full sm:w-1/4 md:w-1/2 lg:max-w-lg">
                <input type="text" placeholder="Tìm kiếm..."
                    class="w-full bg-black text-white py-2 px-4 rounded-full border border-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500" />
                <button type="submit"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
        </div> --}}

        <!-- Navigation Links -->
        <nav class="flex items-center space-x-1 sm:space-x-4">
            <!-- Notification Button -->
            <a href="/upload-song" class="flex items-center space-x-2 hover:text-green-500 hover:bg-gray-600 rounded-full w-10 h-10 flex justify-center items-center" title="Tải lên bài hát">
                <i class="fa-solid fa-upload text-lg"></i>
            </a>
            <button id="notificationButton"
                class="relative text-white rounded-full w-10 h-10 flex justify-center items-center hover:bg-gray-600" title="Thông báo">
                <i class="fa-solid fa-bell"></i>
            </button>
            <a href="/editprofile" class="flex items-center space-x-2 hover:text-green-500 hover:bg-gray-600 rounded-full w-10 h-10 flex justify-center items-center" title="Cài đặt">
                <i class="fa-solid fa-gear text-lg"></i>
            </a>

            <!-- Popup Notification -->
            <div id="notificationPopup"
                class="hidden absolute top-14 right-4 bg-gray-800 text-white p-4 rounded-lg shadow-lg w-[14rem] sm:w-60 md:w-96">
                <p class="font-bold mb-2">Thông báo</p>
                <ul>
                    @foreach ($notifications as $notification)
                        <li class="notification-item {{ $notification->read_at ? '' : 'unread' }}"
                            data-id="{{ $notification->id }}">
                            <strong>{{ $notification->data['song_name'] }}</strong>:
                            {{ $notification->data['message'] }}
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
                <button id="markAllAsRead" class="mt-3 text-sm text-gray-300 hover:text-gray-200">
                    Đánh dấu tất cả là đã đọc
                </button>
                <button id="closePopup" class="mt-3 text-sm text-gray-300 hover:text-gray-200">
                    Đóng
                </button>
            </div>

        </nav>
    </header>

    <!-- Main -->
    <main class="p-4 ml-auto ml-28 lg:ml-40 xl:ml-60 md:mx-auto max-w-4xl">
        <!-- Profile Info Section -->
        <section class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
            <img src="{{ url('image/' . $user->avatar_id) }}" alt="Profile picture"
                class="rounded-full h-48 w-48 object-cover" />
            <div>
                <h2 class="text-4xl font-bold mt-10">{{ $user->name }}</h2>
                <p class="text-gray-400">{{ $user->email }}</p>
                <p class="mt-2 text-gray-300">{{ $user->author->author_name }}</p>
            </div>
        </section>

        <!-- Playlist Section -->
        <section class="mt-10">
            <h3 class="text-2xl font-bold">Your Playlist</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @foreach ($playlists as $playlist)
                    <div class="flex items-center space-x-4">
                        <img src="{{ $playlist->name == 'Liked music' ? 'https://i1.sndcdn.com/artworks-4Lu85Xrs7UjJ4wVq-vuI2zg-t500x500.jpg' : 'http://localhost:8000/images/profile/logo-home.png' }}"
                            alt="Track 1 image placeholder" class="h-16 w-16 object-cover" />
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
                        <img src="https://storage.googleapis.com/a1aa/image/Vh5OymOlhzrpGNSyzTrQJDAuYen3bRBW76631QVNglJuQ87JA.jpg"
                            alt="Album cover" class="w-16 h-16 object-cover" />
                        <div>
                            <p class="text-lg text-white">
                                {{ $song->song_name }} - {{ $song->author->author_name }}
                            </p>
                            <p class="text-gray-500 text-base">{{ $song->play_count }} lượt nghe - {{ $song->likes }}
                                lượt thích</p>
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

        notificationButton.addEventListener("click", function(e) {
            e.stopPropagation(); // Ngăn việc sự kiện click lan ra ngoài
            notificationPopup.classList.toggle("hidden");
        });

        // Đóng popup khi click ra ngoài
        document.addEventListener("click", function(e) {
            if (!notificationPopup.contains(e.target) && !notificationButton.contains(e.target)) {
                notificationPopup.classList.add("hidden");
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Gửi yêu cầu đánh dấu thông báo đã đọc
            function markAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                }).then(response => {
                    if (response.ok) {
                        const notificationItem = document.querySelector(
                            `.notification-item[data-id="${notificationId}"]`);
                        if (notificationItem) {
                            notificationItem.classList.remove('unread');
                        }
                    }
                });
            }

            // Sự kiện click vào thông báo
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    markAsRead(notificationId);
                });
            });

            // Sự kiện đánh dấu tất cả là đã đọc
            document.getElementById('markAllAsRead').addEventListener('click', function() {
                fetch('/notifications/mark-all-as-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        document.querySelectorAll('.notification-item.unread').forEach(item => {
                            item.classList.remove('unread');
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
