<!DOCTYPE html>
<html lang="en" class="overflow-x-hidden w-screen">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nulltifly')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/playout.css'])
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

        body.lock-scroll {
            overflow: hidden;
            touch-action: none;
        }

        #search-form input[type="text"] {
            height: 2.5rem;
            width: 100%;
            font-size: 12px;
            border-radius: 6px;
            position: relative;
            left: 4rem;
            top: 1rem;
        }

        /* #sidebar {
            z-index: 50;
        } */

        #overlayphone {
            z-index: 40;
        }
    </style>
</head>

<body class="m-0 font-sans bg-backround-color text-black overflow-x-hidden w-screen dark:bg-gradient-to-r from-gray-800 via-gray-900 to-black dark:text-white transition-colors duration-300">

    <!-- Nút mở sidebar trên điện thoại -->
    <button id="toggleSidebar" class="lg:hidden text-black absolute top-[2.5rem] left-[1.4rem] z-50 text-2xl dark:text-white">☰</button>
    <div id="overlayphone" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>
    {{-- SideBar --}}
    <div id="sidebar"
        class="sidebar border-r border-gray-500 p-4 w-[250px] bg-black min-h-screen fixed pt-5 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <a class="no-hover block text-white px-8 py-[11px] no-underline text-[18px] hover:bg-[#1a1616]"
            href="{{ route('home') }}">
            <img class="block mx-auto mb-5 w-36 cursor-pointer" alt="Logo" height="100"
                src="http://localhost:8000/images/profile/logo-home.png" width="100" />
        </a>
        <a href="{{ route('home') }}"
            class="{{ request()->routeIs('home') ? 'active' : '' }} block text-white px-8 py-[11px] no-underline text-[18px] hover:bg-gray-700">Home</a>
        <a href="{{ route('library') }}" id="librarys"
            class="{{ request()->routeIs('library') ? 'active' : '' }} block text-white px-8 py-[11px] no-underline text-[18px] hover:bg-gray-700">Library</a>
        <button id="createPlaylistBtn" class="btn-create-playist" onclick="NewPlaylist()"> + Create Playlist </button>
        @foreach ($playlists as $playlist)
            <a href="{{ route('playlist', $playlist->id) }}"
                class="{{ request()->is('get-song-in-playlist/' . $playlist->id) ? 'active' : '' }} block text-white px-8 py-[11px] no-underline text-[18px] hover:bg-gray-700">{{ $playlist->name }}</a>
        @endforeach
    </div>

    <!-- Create Playlist -->
    <div class="relative">
        <!-- Popup form tạo playlist -->
        <div id="createPlaylistPopup"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-80 flex items-center justify-center z-50">
            <div
                class="bg-gray-800 w-full max-w-lg sm:max-w-md md:max-w-sm lg:max-w-lg xl:max-w-xl p-6 rounded-lg shadow-lg relative mx-4">
                <!-- Nút đóng -->
                <button id="closePopupBtn"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-300 text-xl focus:outline-none">
                    &times;
                </button>

                <!-- Tiêu đề -->
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center text-white mb-6">
                    Create Playlist
                </h2>

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

    <div class="main-content !pb-[250px] ml-[100px] p-5">
    <div class="header w-full pb-3 flex justify-between items-center relative right-20  ">
    <!-- Search -->
    <form action="{{ route('searchsong') }}" method="get"
        class="relative flex items-center w-full max-w-[20rem] md:max-w-[30rem] lg:max-w-[40rem] pl-8 pr-12" id="search-form">
        <input name="query" placeholder="What are you looking for?" type="text" id="query"
            class="bg-gray-500 text-gray-black rounded-full h-12 w-full text-sm md:text-base pl-12 pr-14 focus:outline-none placeholder:text-xs md:placeholder:text-sm dark:bg-gray-700 dark:text-white placeholder:text-white transition-colors duration-300" />
        <button type="submit"
            class="absolute right-4 top-[16px] left-[38rem] flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-transparent cursor-pointer">
            <i class="fa-solid fa-magnifying-glass text-[16px] md:text-[20px] text-[#f5efef]"></i>
        </button>
    </form>
    <button id="theme-toggle" class="px-4 py-2 bg-gray-700 text-white font-medium rounded-full hover:bg-gray-600 transition absolute left-[76.5rem] top-3">
       Light 
    </button>

    @if (Auth::check())
        <!-- Nếu người dùng đã đăng nhập -->
        <div id="avatar" class="user flex items-center space-x-2 relative top-3 right-10">
            <span class="nameavatar text-black text-sm md:text-base dark:text-white transition-colors duration-300 ">{{ Auth::user()->name }}</span>
            <img alt="User Avatar" class="rounded-full border border-gray-500"
                src="{{ url('image/' . Auth::user()->avatar_id) }}" width="40" height="40" />
        </div>
        <!-- Popup Profile / Logout -->
        <div id="popup" class="avatar-popup hidden bg-gray-800 text-white p-4 rounded-lg shadow-lg relative right-[16px] top-2 z-10">
            <ul class="space-y-2">
                <li class="flex items-center space-x-2">
                    <i class="fa-solid fa-user"></i>
                    <a href="/profile/{{ Auth::user()->id }}" class="hover:text-gray-300">Profile</a>
                </li>
                <li class="flex items-center space-x-2">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit"
                            class="flex items-center space-x-2 hover:text-gray-300">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @else
        <!-- Nếu người dùng chưa đăng nhập -->
        <div class="auth-links flex justify-between items-center absolute top-[21px] left-[72rem] cursor-pointer ">
            <a onclick="showLoginForm()" class="text-black text-sm md:text-base no-underline hover:text-[#68aee0] dark:text-white">Login</a>
        </div>
    @endif
</div>
        @yield('content')

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
                    <div class="flex items-center mb-4 cursor-pointer" onclick="toggleSelection(this)"
                        data-playlist-id="{{ $playlist->id }}">
                        <img alt="Heart icon" class="w-6 h-6 rounded mr-3"
                            src="https://storage.googleapis.com/a1aa/image/B52gnTORR458O56CfplN0UUXr2vJMWPUie257n5NYDgJkH2TA.jpg"
                            width="24" height="24" />
                        <span class="flex-1 text-white">{{ $playlist->name }}</span>
                        <i class="fas fa-check-circle text-green-500 mr-2 !hidden"></i>
                    </div>
                @endforeach
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
    @include('components.footer')
    <script>
        //*Phần liên quan tới reponsive*

        //Nút Hiện sidebar trên điện thoại
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlayphone');
        const maincontent = document.querySelector('.main-content');
        const body = document.body;

        // Hàm mở/đóng sidebar
        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            body.classList.add('lock-scroll'); // Thêm class chặn cuộn
        };
        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            body.classList.remove('lock-scroll'); // Xóa class chặn cuộn
        };

        // Xử lý nút mở sidebar
        toggleSidebar.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
        // Xử lý khi nhấn vào overlay
        overlay.addEventListener('click', closeSidebar);

        // Hàm tùy chỉnh khi thay đổi kích thước màn hình
        function handleResize() {
            if (window.innerWidth >= 1024) { // Kích thước màn hình lớn (desktop)
                sidebar.style.zIndex = ''; // Xóa z-index của sidebar để không bị đè footer
                maincontent.style.marginLeft = '250px'; // Đặt margin-left cho main content
            } else { // Kích thước màn hình nhỏ (điện thoại, tablet)
                sidebar.style.zIndex = '50'; // Đặt z-index cho sidebar
                maincontent.style.marginLeft = '0'; // Xóa margin-left
            }
        }

        // Gọi hàm ngay khi tải trang
        handleResize();

        // Lắng nghe sự kiện thay đổi kích thước cửa sổ
        window.addEventListener('resize', handleResize);










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
            const popup = document.getElementById('popup');

            const avatar = document.querySelector('#avatar'); // Lấy phần tử #avatar
            const avatarPopup = document.querySelector('.avatar-popup'); // Lấy phần tử pop-up

            if (!avatar || !avatarPopup) return;

            // Mở popup khi nhấn vào avatar
            avatar.addEventListener('click', function(e) {
                e.stopPropagation(); // Ngăn chặn sự kiện ngoài từ việc ẩn pop-up
                avatarPopup.classList.toggle('hidden'); // Thêm hoặc xóa class 'block' cho popup
            });

            // Ẩn popup khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!avatarPopup.contains(e.target) && !avatar.contains(e.target)) {
                    avatarPopup.classList.remove('block'); // Ẩn pop-up
                };
            });

            // Ngăn pop-up bị tắt khi click bên trong
            avatarPopup.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            // Lấy các phần tử popup và nút
            var playlistPopup = document.getElementById("createPlaylistPopup");
            var closePopupBtn = document.getElementById("closePopupBtn");

            // Đóng popup khi nhấn nút đóng
            closePopupBtn.addEventListener("click", function() {
                playlistPopup.style.display = "none";
            });

            // Đóng popup nếu người dùng nhấn ra ngoài popup
            window.addEventListener("click", function(event) {
                if (event.target == playlistPopup) {
                    playlistPopup.style.display = "none";
                }
            });

            const toggleLyricsBtn = document.getElementById("toggleLyricsIcon"); // Nút play làm trigger
            const lyricPopup = document.getElementById("lyricPopup");

            let isLyricsVisible = false; // Trạng thái hiển thị lyrics

            // Toggle popup lyrics
            toggleLyricsBtn.addEventListener("click", () => {
                isLyricsVisible = !isLyricsVisible;

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
            let user_id = document.getElementById('user_id').value.trim();
            if (!name) {
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
                    user_id
                }),
                success: function(data) {
                    var popup = document.getElementById("createPlaylistPopup");
                    popup.style.display = "none";
                    document.getElementById('title-playlist').value = '';
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
                deleteSongFromPlaylist = deleteSongFromPlaylist.filter(id => id !== playlistId);
                selectedPlaylists.push(playlistId);
                // Bỏ dấu tích (hiện icon check)
                checkIcon.classList.remove('!hidden');
            } else {
                selectedPlaylists = selectedPlaylists.filter(id => id !== playlistId);
                deleteSongFromPlaylist.push(playlistId);
                // Đặt lại dấu tích (ẩn icon check)
                checkIcon.classList.toggle('!hidden');
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
            deleteSongFromPlaylist.forEach(playlistId => {
                fetch('/delete-song-in-playlist', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: JSON.stringify({
                        playlist_id: playlistId,
                        song_id: songId,
                    })
                });
            });

            // Sau khi gửi, đóng popup
            closePopup();
        }
        const user = {
            isLoggedIn: {{ Auth::check() ? 'true' : 'false' }},
            plan: "{{ Auth::check() ? Auth::user()->plan : 'guest' }}"
        };
        window.user = user;
    </script>
    @stack('scripts')
    @vite('resources/js/app.js')

</body>


</html>
