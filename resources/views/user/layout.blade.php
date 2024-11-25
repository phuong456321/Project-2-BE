<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    @vite('resources/css/playout.css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    @stack('styles')
    <style>
        .sidebar a.active {
            background-color: #444;
            color: white;
            font-weight: bold;
            border-left: 5px solid #00f;
        }

        /* Popup styles */
        .avatar-popup {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 180px;
            padding: 10px 0;
        }

        .avatar-popup ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .avatar-popup ul li a {
            display: block;
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
        }

        .avatar-popup ul li a:hover {
            background-color: #f1f1f1;
        }
    </style>
    <script>
        // pop up create playist
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy các phần tử popup và nút
            var createPlaylistBtn = document.getElementById("createPlaylistBtn");
            var popup = document.getElementById("createPlaylistPopup");
            var closePopupBtn = document.getElementById("closePopupBtn");

            // Hiển thị popup khi nhấn nút "Create Playlist"
            createPlaylistBtn.addEventListener("click", function() {
                popup.style.display = "block";
            });

            // Đóng popup khi nhấn nút đóng
            closePopupBtn.addEventListener("click", function() {
                //  console.log("Close button clicked");
                popup.style.display = "none";
            });

            // Đóng popup nếu người dùng nhấn ra ngoài popup
            window.addEventListener("click", function(event) {
                if (event.target == popup) {
                    popup.style.display = "none";
                }
            });
        });
    </script>
</head>

<body>

    <div class="sidebar">
        <a class="no-hover" href="{{ route('home') }}">
            <img alt="Logo" height="100" src="images/profile/logo-home.png" width="100" />
        </a>
        <a href="{{ route('home') }}" id="home" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="/library" id="librarys" class="{{ request()->is('library') ? 'active' : '' }}">Library</a>
        <a href="/playist" id="playist" class="{{ request()->is('playist') ? 'active' : '' }}">Playlist</a>
        <button id="createPlaylistBtn" class="btn-create-playist"> + Create Playlist </button>
        <a href="/likesong" class="{{ request()->is('likesong') ? 'active' : '' }}">Like songs</a>
        <a href="#" class="{{ request()->is('') ? 'active' : '' }}">My Album</a>
        <a href="/playist" class="{{ request()->is('playlist1') ? 'active' : '' }}">Playlist1</a>

        <!-- Avatar Section -->
        <div class="relative">
            <div id="popup" class="avatar-popup hidden">
                <ul>
                    <li><a href="/profile" class="block px-4 py-2">Profile</a></li>
                    <li>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Popup form tạo playlist -->
        <div id="createPlaylistPopup" class="popup">
            <div class="popup-content">
                <span class="close-btn" id="closePopupBtn">&times;</span>
                <h2>Create Playlist</h2>
                <form id="createPlaylistForm">
                    <input type="text" id="title" name="title" placeholder="Tiêu đề" required><br><br>
                    <textarea id="description" name="description" placeholder="Mô tả" required></textarea><br><br>
                    <!-- <label for="privacy">Privacy:</label>
                    <select id="privacy" name="privacy" required>
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select><br><br> -->

                    <button id="btn-createplayist" type="submit">Create</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>
</body>

</html>