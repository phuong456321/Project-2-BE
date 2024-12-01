<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nulltifly')</title>
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
        //Lyrics popup
        document.addEventListener("DOMContentLoaded", () => {
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
                    }, 10); // Thêm chút độ trễ nhỏ để cho phép class 'hidden' thay đổi trước khi 'show' được áp dụng
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

        //add song
        // Open popup
        function openPopup() {
            document.getElementById('overlay').classList.add('active');
        }

        // Close popup
        function closePopup() {
            document.getElementById('overlay').classList.remove('active');
        }

        // Function to filter playlists
        function filterPlaylists() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const playlists = document.querySelectorAll('#playlists > div');
            playlists.forEach(playlist => {
                const text = playlist.innerText.toLowerCase();
                playlist.style.display = text.includes(query) ? '' : 'none';
            });
        }

        // Function to create a new playlist
        function createNewPlaylist() {
            const newPlaylistName = prompt('Nhập tên danh sách phát mới:');
            if (newPlaylistName) {
                const playlistsContainer = document.getElementById('playlists');
                const newPlaylist = document.createElement('div');
                newPlaylist.className = 'bg-gray-600 flex items-center mb-4 cursor-pointer';
                newPlaylist.setAttribute('onclick', 'toggleSelection(this)');
                newPlaylist.innerHTML = `
          <img alt="New playlist icon" class="w-6 h-6 rounded mr-3" src="https://via.placeholder.com/24" width="24" height="24"/>
          <span class="flex-1 text-white">${newPlaylistName}</span>
          <i class="fas fa-check-circle text-green-500 mr-2 hidden"></i>
        `;
                playlistsContainer.appendChild(newPlaylist);
                alert('Danh sách phát mới đã được thêm!');
            }
        }

        // Function to toggle selection of a playlist
        function toggleSelection(element) {
            const checkIcon = element.querySelector('i.fas.fa-check-circle');

            // Chuyển trạng thái của dấu tích
            if (checkIcon.classList.contains('hidden')) {
                checkIcon.classList.remove('hidden');
            } else {
                checkIcon.classList.add('hidden');
            }
        }


        // Function to confirm selection
        function confirmSelection() {
            const selected = document.querySelector('.fa-check-circle:not(.hidden)');
            if (selected) {
                const playlistName = selected.parentNode.querySelector('span').innerText;
                alert(`Đã thêm vào danh sách phát: "${playlistName}"`);
                closePopup();
            } else {
                alert('Vui lòng chọn một danh sách phát!');
            }
        }
    </script>
</head>

<body>

    <div class="sidebar">
        <a class="no-hover" href="{{ route('home') }}">
            <img alt="Logo" height="100" src="images/profile/logo-home.png" width="100" />
        </a>
        <a href="{{ route('home') }}" id="home" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{route('library')}}" id="librarys" class="{{ request()->is('library') ? 'active' : '' }}">Library</a>
        <a href="/playist" id="playist" class="{{ request()->is('playist') ? 'active' : '' }}">Playlist</a>
        <button id="createPlaylistBtn" class="btn-create-playist"> + Create Playlist </button>
        <a href="/likesong" class="{{ request()->is('likesong') ? 'active' : '' }}">Like songs</a>
        <a href="#" class="{{ request()->is('') ? 'active' : '' }}">My Album</a>
        <a href="/playist" class="{{ request()->is('playlist1') ? 'active' : '' }}">Playlist1</a>
    </div>
    
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
        <!-- Popup form tạo playlist -->
        <div id="createPlaylistPopup" class="popup hidden">
            <div class="popup-content">
                <span class="close-btn" id="closePopupBtn">&times;</span>
                <h2>Create Playlist</h2>
                <form id="createPlaylistForm">
                    <input type="text" id="title" name="title" placeholder="Tiêu đề" required class="w-full px-3 py-2 border border-gray-300 rounded mb-4">
                    <textarea id="description" name="description" placeholder="Mô tả" required class="w-full px-3 py-2 border border-gray-300 rounded mb-4"></textarea>
                    <button id="btn-createplayist" type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Create
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="main-content">
        {{-- <div class="header">
            <input id="searchInput" placeholder="Bạn đang tìm kiếm gì?" type="text" />
    
            @if (Auth::check())
                <-- Nếu người dùng đã đăng nhập -->
                <div id="avatar" class="user" onclick="togglePopup()">
                    <span>{{ Auth::user()->name }}</span>
                    <img alt="User Avatar" class="rounded-full" height="40"
                        src="data:image/jepg;base64,{{ Auth::user()->avatar_id ? App\Models\Image::where('img_id', Auth::user()->avatar_id)->first()->img_path ?? asset('images/default-avatar.jpg') : asset('images/default-avatar.jpg') }}"
                        width="40" />
                </div>
    
                <!-- Popup Profile / Logout -->
                <div id="popup" class="avatar-popup hidden">
                    <ul>
                        <li><a href="/profile/{{ Auth::user()->id }}">Profile</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="logout-btn">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <-- Nếu người dùng chưa đăng nhập -->
                <div class="auth-links">
                    <a href="javascript:void(0)" onclick="showLoginForm()" class="login-link">Login</a>
                    <span class="separator">/</span>
                    <a href="javascript:void(0)" onclick="showRegisterForm()" class="register-link">Register</a>
                </div>
            @endif
        </div> --}}
        @yield('content')
    </div>
    <div class="footer">
        <div class="loader">
            <div class="justify-content-center jimu-primary-loading"></div>
        </div>
        <img src="" alt="">
        <div class="controls">

            <i class="fas fa-step-backward">
            </i>
            <i class="fas fa-play">
            </i>
            <i class="fas fa-step-forward">
            </i>
        </div>
        <div class="progress">
            <input max="100" min="0" type="range" value="30" />
            <p>
                3:39 / 4:10
            </p>
        </div>
        <div class="current-song">
            <p>
                CANXA
            </p>
            <p>
                1DEE và FEEZY
            </p>
        </div>
        <div class="actions">
            <i class="fas fa-heart">
            </i>
            <i class="fas fa-random">
            </i>
            <i class="fas fa-volume-up">
            </i>
            <i class="fa-solid fa-music" id="toggleLyricsIcon">
            </i>
            <i onclick="openPopup()" class="fas fa-ellipsis-h"></i>
        </div>




    </div>
    <!-- Popup lyrics -->
    <div id="lyricPopup" class="popup-lyrics hidden">
        <div class="popup-lyrics-content">
            <div class="lyrics-container">
                <div class="left">
                    <img
                        alt="Album cover with text 'SƠN TÙNG M-TP CÓ CHẮC YÊU LÀ ĐÂY' and a person looking down"
                        height="600"
                        src="https://1.bp.blogspot.com/-capieOTmKV4/XSFjKrRJ-hI/AAAAAAAANuM/1VXeYK1rg88CEqlEDa6wESWFumYqFTIBACLcBGAs/s1600/bo-hinh-nen-son-tung-mtp-cute-dep-nhat-cho-dien-thoai-trong-mv-hay-trao-cho-anh-8.jpg"
                        width="600" />
                </div>
                <div class="right">
                    <div class="tabs">
                        <div class="tab active">LYRIC</div>
                    </div>
                    <div class="lyrics">
                        <p>
                            (Thấp thoáng ánh mắt, thấp thoáng ánh mắt)
                            <br />
                            (Thấp thoáng ánh mắt, thấp thoáng ánh mắt)
                            <br />
                            Good boy
                        </p>
                        <p>
                            Thấp thoáng ánh mắt đôi môi mang theo hương mê say
                            <br />
                            Em cho anh tan trong miên man quên luôn đi đêm ngày
                            <br />
                            Chạm nhẹ vội vàng hai ba giây nhưng con tim đâu hay
                            <br />
                            Bối rối khẽ lên ngôi yêu thương đong đầy thật đầy
                        </p>
                        <p>
                            Anh ngẩn ngơ cứ ngỡ
                            <br />
                            (Đó chỉ là giấc mơ)
                            <br />
                            Anh ngẩn ngơ cứ ngỡ
                            <br />
                            (Như đang ngất ngây trong giấc mơ)
                            <br />
                            Thật ngọt ngào êm dịu đắm chìm
                            <br />
                            Phút chốc viết tương tư gieo nên thơ (yeah, hey)
                        </p>
                        <p>
                            Có câu ca trong gió hát ngân nga, ru trôi mây
                            <br />
                            Nhẹ nhàng đón ban mai ngang qua trao nụ cười (trao nụ cười)
                            <br />
                            Nắng đưa chen, khóe sắc, vui đùa giữa muôn ngàn hoa
                            <br />
                            Dưới ánh nắng dịu dàng âu yếm tâm hồn người
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay and Modal -->
    <div id="overlay" class="overlay">
        <div class="bg-gray-800 text-white rounded-lg p-4 w-80 relative">
            <!-- Close Button -->
            <button
                class="absolute top-2 right-2 text-gray-400 hover:text-white"
                onclick="closePopup()">
                <i class="fas fa-times"></i>
            </button>
            <!-- Header -->
            <div class="text-lg font-semibold mb-4">
                Thêm vào danh sách phát
            </div>
            <!-- Search Input -->
            <div class="relative mb-4">
                <input
                    id="searchInput"
                    class="w-full bg-gray-700 text-white rounded-full py-2 pl-10 pr-4 focus:outline-none"
                    placeholder="Tìm một danh sách phát"
                    type="text"
                    oninput="filterPlaylists()" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <!-- New Playlist Option -->
            <div class="flex items-center mb-4 cursor-pointer" onclick="createNewPlaylist()">
                <i class="fas fa-plus-circle text-xl text-gray-400 mr-3"></i>
                <span class="text-white">Danh sách phát mới</span>
            </div>
            <!-- Playlists -->
            <div id="playlists">
                <div class="flex items-center mb-4 cursor-pointer" onclick="toggleSelection(this)">
                    <img alt="Heart icon" class="w-6 h-6 rounded mr-3" src="https://storage.googleapis.com/a1aa/image/B52gnTORR458O56CfplN0UUXr2vJMWPUie257n5NYDgJkH2TA.jpg" width="24" height="24" />
                    <span class="flex-1 text-white">Bài hát đã thích</span>
                    <i class="fas fa-check-circle text-green-500 mr-2 hidden"></i>
                </div>
                <div class="flex items-center mb-4 cursor-pointer" onclick="toggleSelection(this)">
                    <img alt="Music note icon" class="w-6 h-6 rounded mr-3" src="https://storage.googleapis.com/a1aa/image/YFIUdA8GQWayP5XJap5gjCDpFVeTf1DB7k9ZhAmh1foRIPsnA.jpg" width="24" height="24" />
                    <span class="flex-1 text-white">Danh sách phát của tôi ...</span>
                    <i class="fas fa-check-circle text-green-500 mr-2 hidden"></i>
                </div>
            </div>
            <!-- Buttons -->
            <div class="flex justify-between items-center mt-4">
                <button
                    class="bg-gray-700 text-gray-400 py-2 px-4 rounded-lg hover:bg-gray-600"
                    onclick="closePopup()">
                    Hủy
                </button>
                <button
                    class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600"
                    onclick="confirmSelection()">
                    Thêm
                </button>
            </div>
        </div>
</body>

</html>