<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nulltifly')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/playout.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.1.0/shaka-player.compiled.js"></script>
    @stack('styles')
    <style>
        .sidebar a.active {
            background-color: #444;
            color: white;
            font-weight: bold;
            border-left: 5px solid #00f;
        }

        /* Style for overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .overlay.active {
            display: flex;
        }

        .search-form {
            width: 60%;
            position: relative;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .search-form input {
            width: 90%;
        }

        .search-form input[type="text"] {
            right: 0;
        }

        .search-song-icon i {
            position: relative;
            left: 0;
            top: 0;
            font-size: 20px;
            color: #f5efef;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a class="no-hover" href="{{ route('home') }}">
            <img alt="Logo" height="100" src="http://localhost:8000/images/profile/logo-home.png"
                width="100" />
        </a>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('library') }}" id="librarys"
            class="{{ request()->routeIs('library') ? 'active' : '' }}">Library</a>
        <button id="createPlaylistBtn" class="btn-create-playist" onclick="NewPlaylist()"> + Create Playlist </button>
        @foreach ($playlists as $playlist)
            <a href="{{ route('playlist', $playlist->id) }}" class="{{ request()->is('get-song-in-playlist/' . $playlist->id) ? 'active' : '' }}">{{ $playlist->name }}</a>
        @endforeach
    </div>

    <!-- Create Playlist -->
    <div class="relative">
        <!-- Popup form tạo playlist -->
        <div id="createPlaylistPopup"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-80 flex items-center justify-center z-50">
            <div class="bg-gray-800 w-full max-w-lg p-6 rounded-lg shadow-lg relative">
                <!-- Nút đóng -->
                <button id="closePopupBtn"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-300 text-xl focus:outline-none">
                    &times;
                </button>

                <!-- Tiêu đề -->
                <h2 class="text-2xl font-bold text-center text-white mb-6">Create Playlist</h2>

                <!-- Form -->
                <form id="createPlaylistForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id" value="">

                    <!-- Tiêu đề -->
                    <input type="text" id="title-playlist" name="title" placeholder="Tiêu đề" required
                        class="w-full px-4 py-3 border border-gray-600 rounded-lg shadow-sm bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-x-gray-800 focus:border-x-gray-800" />

                    <!-- Nút tạo -->
                    <button id="btn-createplayist" type="submit"
                        class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-gray-600 focus:ring-2 focus:ring-gray-600 focus:ring-offset-1 focus:outline-none">
                        Create
                    </button>
                </form>
            </div>
        </div>
    </div>


    <div class="main-content">
        <div class="header">
            <!-- Search -->
            <form action="{{ route('searchsong') }}" method="get" class="search-form" id="search-form">
                <input name="query" placeholder="Bạn đang tìm kiếm gì?" type="text" id="query" />
                <button type="submit" class="search-song-icon">
                    <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                </button>
            </form>
            @if (Auth::check())
                {{-- Nếu người dùng đã đăng nhập --}}
                <div id="avatar" class="user">
                    <span>{{ Auth::user()->name }}</span>
                    <img alt="User Avatar" class="rounded-full" height="40"
                        src="{{ url('image/' . Auth::user()->avatar_id) }}" width="40" />
                </div>
                <!-- Popup Profile / Logout -->
                <div id="popup" class="avatar-popup hidden">
                    <ul>
                        <li>
                            <i class="fa-solid fa-user"></i>
                            <a href="/profile/{{ Auth::user()->id }}">
                                Profile
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <div class="icon-wrapper">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </div>
                                <button type="submit" class="logout-btn">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                {{-- Nếu người dùng chưa đăng nhập --}}
                <div class="auth-links">
                    <a onclick="showLoginForm()" class="login-link">Login</a>
                    <span class="separator">/</span>
                    <a onclick="showRegisterForm()" class="register-link">Register</a>
                </div>
            @endif
        </div>
        @yield('content')
    </div>

    <!-- Overlay and Modal -->
    <div id="overlay" class="overlay">
        <div class="bg-gray-800 text-white rounded-lg p-4 w-80 relative">
            <!-- Close Button -->
            <button class="absolute top-2 right-2 text-gray-400 hover:text-white" onclick="closePopup()">
                <i class="fas fa-times"></i>
            </button>
            <!-- Header -->
            <div class="text-lg font-semibold mb-4">
                Thêm vào danh sách phát
            </div>
            <!-- Search Input -->
            <div class="relative mb-4">
                <input id="searchInput"
                    class="w-full bg-gray-700 text-white rounded-full py-2 pl-10 pr-4 focus:outline-none"
                    placeholder="Tìm một danh sách phát" type="text" oninput="filterPlaylists()" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <!-- New Playlist Option -->
            <div class="flex items-center mb-4 cursor-pointer" onclick="NewPlaylist()">
                <i class="fas fa-plus-circle text-xl text-gray-400 mr-3"></i>
                <span class="text-white">Danh sách phát mới</span>
            </div>
            <!-- Playlists -->
            <div id="playlists">
                @foreach ($playlists as $playlist)
                    <div class="flex items-center mb-4 cursor-pointer" onclick="toggleSelection(this)">
                        <img alt="Heart icon" class="w-6 h-6 rounded mr-3"
                            src="https://storage.googleapis.com/a1aa/image/B52gnTORR458O56CfplN0UUXr2vJMWPUie257n5NYDgJkH2TA.jpg"
                            width="24" height="24" />
                        <span class="flex-1 text-white">{{ $playlist->name }}</span>
                        <i class="fas fa-check-circle text-green-500 mr-2 hidden" style="display: none;"></i>
                    </div>
                @endforeach
                <div class="flex items-center mb-4 cursor-pointer" onclick="toggleSelection(this)">
                    <img alt="Music note icon" class="w-6 h-6 rounded mr-3"
                        src="https://storage.googleapis.com/a1aa/image/YFIUdA8GQWayP5XJap5gjCDpFVeTf1DB7k9ZhAmh1foRIPsnA.jpg"
                        width="24" height="24" />
                    <span class="flex-1 text-white">Danh sách phát của tôi ...</span>
                    <i class="fas fa-check-circle text-green-500 mr-2 hidden"></i>
                </div>
            </div>
            <!-- Buttons -->
            <div class="flex justify-between items-center mt-4">
                <button class="bg-gray-700 text-gray-400 py-2 px-4 rounded-lg hover:bg-gray-600"
                    onclick="closePopup()">
                    Hủy
                </button>
                <button class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600"
                    onclick="confirmSelection()">
                    Thêm
                </button>
            </div>
        </div>
    </div>
    <div id="flash-message"
        class="hidden fixed top-4 right-4 bg-blue-500 text-white py-2 px-4 rounded-lg shadow-lg z-50"></div>
</body>
<script>
    document.getElementById('search-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const searchInput = document.getElementById('query');
        const query = searchInput.value.trim(); // Lấy từ khóa tìm kiếm
        const userId = {{ Auth::check() ? Auth::user()->id : 'null' }};
        if (query.length > 0) {
            // Gửi dữ liệu tìm kiếm qua AJAX
            $.ajax({
                    url: '/save-search-history',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    data: JSON.stringify({
                        user_id: userId,
                        content: query,
                        clicked_song_id: null, // Không có bài hát nào được nhấn
                    }),
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            // Tiến hành tìm kiếm như bình thường
            window.location.href = '/search?query=' + encodeURIComponent(query);
        }
    });

    function flash(message, type = 'success') {
        // Lấy phần tử flash message
        const flashMessage = document.getElementById('flash-message');

        // Cập nhật nội dung và kiểu dáng dựa trên loại thông báo
        flashMessage.textContent = message;
        flashMessage.className =
            `fixed top-4 right-4 py-2 px-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        // Hiển thị thông báo
        flashMessage.style.display = 'block';
        // Ẩn sau 3 giây
        setTimeout(() => {
            flashMessage.classList.add('fade-out');
            setTimeout(() => {
                flashMessage.style.display = 'none';
                flashMessage.classList.remove('fade-out');
            }, 500); // Chờ hiệu ứng kết thúc
        }, 3000);
    }
    // pop up create playist
    document.addEventListener("DOMContentLoaded", function() {
        const avatar = document.querySelector('#avatar'); // Lấy phần tử #avatar
        const avatarPopup = document.querySelector('.avatar-popup'); // Lấy phần tử pop-up

        if (!avatar || !avatarPopup) return;

        // Mở popup khi nhấn vào avatar
        avatar.addEventListener('click', function(e) {
            e.stopPropagation(); // Ngăn chặn sự kiện ngoài từ việc ẩn pop-up
            avatarPopup.classList.toggle('block'); // Thêm hoặc xóa class 'block' cho popup
        });

        // Ẩn popup khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!avatarPopup.contains(e.target) && !avatar.contains(e.target)) {
                avatarPopup.classList.remove('block'); // Ẩn pop-up
            }
        });

        // Ngăn pop-up bị tắt khi click bên trong
        avatarPopup.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        // Lấy các phần tử popup và nút
        var popup = document.getElementById("createPlaylistPopup");
        var closePopupBtn = document.getElementById("closePopupBtn");

        // Đóng popup khi nhấn nút đóng
        closePopupBtn.addEventListener("click", function() {
            popup.style.display = "none";
        });

        // Đóng popup nếu người dùng nhấn ra ngoài popup
        window.addEventListener("click", function(event) {
            if (event.target == popup) {
                popup.style.display = "none";
            }
        });

        const toggleLyricsBtn = document.getElementById("toggleLyricsIcon"); // Nút play làm trigger
        const lyricPopup = document.getElementById("lyricPopup");

        let isLyricsVisible = false; // Trạng thái hiển thị lyrics

        // Toggle popup lyrics
        toggleLyricsBtn.addEventListener("click", () => {
            isLyricsVisible = !isLyricsVisible;
            console.log('isLyricsVisible:', isLyricsVisible);

            if (isLyricsVisible) {
                // Mở popup, thêm độ trễ nhỏ để hiệu ứng trượt lên
                lyricPopup.classList.remove("hidden");
                setTimeout(() => {
                        lyricPopup.classList.add("show");
                    },
                    10
                ); // Thêm chút độ trễ nhỏ để cho phép class 'hidden' thay đổi trước khi 'show' được áp dụng
                // Khi popup mở, thêm lớp no-scroll vào body để ngừng cuộn trang
                document.body.classList.add("no-scroll");
            } else {
                // Đóng popup, thêm độ trễ nhỏ để hiệu ứng trượt xuống
                lyricPopup.classList.remove("show");
                setTimeout(() => {
                    lyricPopup.classList.add("hidden");
                }, 500); // Đảm bảo cho animation trượt xuống hoàn thành trước khi ẩn đi
                // Khi popup đóng, xóa lớp no-scroll để khôi phục cuộn trang
                document.body.classList.remove("no-scroll");
            }
        });
    });

    // Open popup
    function openPopup() {
        document.getElementById('overlay').classList.add('active');
    }

    // Close popup
    function closePopup() {
        document.getElementById('overlay').classList.remove('active');
    }

    // Function to create a new playlist
    function NewPlaylist() {
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        const userId = {{ auth()->check() ? auth()->user()->id : 'null' }};
        console.log('isLoggedIn:', isLoggedIn);
        console.log('userId:', userId);
        if (isLoggedIn) {
            closePopup();
            document.getElementById('user_id').value = userId;
            var popup = document.getElementById("createPlaylistPopup");
            popup.style.display = "flex";
            const form = document.getElementById('createPlaylistForm');
            console.log('new playlist');
            form.addEventListener('submit', async function(event) {
                event.preventDefault(); // Ngăn form reload trang
                createNewPlaylist();
            });
        } else {
            closePopup();
            flash('Vui lòng đăng nhập để tạo playlist!', 'error');
        }
    }

    function createNewPlaylist() {
        console.log('create new playlist');
        let name = document.getElementById('title-playlist').value.trim();
        let description = document.getElementById('description-playlist').value.trim();
        let user_id = document.getElementById('user_id').value.trim();
        if (!name || !description) {
            alert('Vui lòng nhập đầy đủ thông tin.');
            return;
        }

        $.ajax({
            url: '/create-playlist',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            data: JSON.stringify({
                name,
                description,
                user_id
            }),
            success: function(data) {
                var popup = document.getElementById("createPlaylistPopup");
                popup.style.display = "none";
                document.getElementById('title-playlist').value = '';
                document.getElementById('description-playlist').value = '';
                flash('Danh sách phát mới đã được thêm!', 'success');

            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                flash('Có lỗi xảy ra khi thêm danh sách phát.', 'error');
            }
        });
    }


    function toggleSelection(element) {
        const checkIcon = element.querySelector('i.fas.fa-check-circle');
        const playlistId = element.getAttribute('data-playlist-id');
        // Kiểm tra xem dấu tích đã có class 'hidden' chưa
        if (checkIcon.classList.contains('!hidden')) {
            selectedPlaylists.push(playlistId);
            // Bỏ dấu tích (hiện icon check)
            checkIcon.classList.remove('!hidden');
        } else {
            selectedPlaylists = selectedPlaylists.filter(id => id !== playlistId);
            // Đặt lại dấu tích (ẩn icon check)
            checkIcon.classList.add('!hidden');
        }
    }

    function confirmSelection() {
        const songId = document.getElementById('footer').getAttribute('data-song-id');
        selectedPlaylists.forEach(playlistId => {
            fetch('/add-song-to-playlist', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // CSRF token
                    },
                    body: JSON.stringify({
                        playlist_id: playlistId,
                        song_id: songId,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    flash('Bài hát đã được thêm vào danh sách phát!', 'success');
                    console.log(`Playlist ${playlistId} updated successfully`, data);
                })
                .catch(error => {
                    flash('Có lỗi xảy ra khi thêm bài hát vào danh sách phát!', 'error');
                    console.error(`Error updating playlist ${playlistId}:`, error);
                });
        });

        // Sau khi gửi, đóng popup
        closePopup();
    }
</script>

</html>
