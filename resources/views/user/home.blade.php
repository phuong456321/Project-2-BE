<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Music</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



    @vite('resources/css/style.css')
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Đảm bảo cả hai overlay đều ẩn khi tải trang
            document.getElementById("loginOverlay").style.display = "none";
            document.getElementById("registerOverlay").style.display = "none";
        });

        function showLoginForm() {
            document.getElementById("registerOverlay").style.display = "none"; // Ẩn form đăng ký
            document.getElementById("loginOverlay").style.display = "flex"; // Hiển thị form đăng nhập
        }

        function showRegisterForm() {
            document.getElementById("loginOverlay").style.display = "none"; // Ẩn form đăng nhập
            document.getElementById("registerOverlay").style.display = "flex"; // Hiển thị form đăng ký
        }

        function closeOverlay() {
            // Đóng tất cả overlay
            document.getElementById("loginOverlay").style.display = "none";
            document.getElementById("registerOverlay").style.display = "none";
        }

        // Đóng form khi nhấn ra ngoài
        // window.onclick = function(event) {
        //     if (event.target === document.getElementById("loginOverlay")) {
        //         closeOverlay();
        //     } else if (event.target === document.getElementById("registerOverlay")) {
        //         closeOverlay();
        //     }
        // };
        document.addEventListener('DOMContentLoaded', function() {
            const avatar = document.querySelector('#avatar'); // Lấy phần tử #avatar
            const popup = document.querySelector('.avatar-popup'); // Lấy phần tử pop-up

            if (!avatar || !popup) return;

            // Mở popup khi nhấn vào avatar
            avatar.addEventListener('click', function(e) {
                e.stopPropagation(); // Ngăn chặn sự kiện ngoài từ việc ẩn pop-up
                popup.classList.toggle('block'); // Thêm hoặc xóa class 'block' cho popup
            });

            // Ẩn popup khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!popup.contains(e.target) && !avatar.contains(e.target)) {
                    popup.classList.remove('block'); // Ẩn pop-up
                }
            });

            // Ngăn pop-up bị tắt khi click bên trong
            popup.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

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
                newPlaylist.className = 'flex items-center mb-4 cursor-pointer';
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

            // Kiểm tra xem dấu tích đã có class 'hidden' chưa
            if (checkIcon.classList.contains('hidden')) {
                // Bỏ dấu tích (hiện icon check)
                checkIcon.classList.remove('hidden');
            } else {
                // Đặt lại dấu tích (ẩn icon check)
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
        <a href="{{ route('home') }}" id="home" class="{{ request()->is('home') ? 'active' : '' }}">
            Home
        </a>
        <a href="/library" id="librarys">
            Library
        </a>
        <a href="/playist" id="playist">
            Playist
        </a>
        <button id="createPlaylistBtn" class="btn-create-playist"> + Create Playlist </button>
        <a href="/likesong" id="likesong">
            Like songs
        </a>
        <a href="/albums" id="librarys">
            My Album
        </a>
        <a href="/playist" id="playist">
            Playlist 1
        </a>
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
        <div class="header">
            <input placeholder="Bạn đang tìm kiếm gì?" type="text" />
            <a class="search-song-icon" href="/searchsong">
                <i class="fa-solid fa-magnifying-glass fa-lg"></i>
            </a>
            @if (Auth::check())
            {{-- Nếu người dùng đã đăng nhập --}}
            <div id="avatar" class="user" onclick="togglePopup()">
                <span>{{ Auth::user()->name }}</span>
                <img alt="User Avatar" class="rounded-full" height="40"
                    src="data:image/jepg;base64,{{ Auth::user()->avatar_id ? App\Models\Image::where('img_id', Auth::user()->avatar_id)->first()->img_path ?? asset('images/default-avatar.jpg') : asset('images/default-avatar.jpg') }}"
                    width="40" />
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
                <a href="javascript:void(0)" onclick="showLoginForm()" class="login-link">Login</a>
                <span class="separator">/</span>
                <a href="javascript:void(0)" onclick="showRegisterForm()" class="register-link">Register</a>
            </div>
            @endif
        </div>

        <div class="prevalent">
            <div class="info">
                <h2>
                    Prevalent
                </h2>
                <p>
                    Dangrangto
                </p>
                <p>
                    AKA Trần Lá Lướt
                </p>
                <button>
                    Listen now
                </button>
            </div>

            <div class="image-slider">
                <div class="image-track">
                    <img src="images/profile/hinh tao.jpg" alt="Image 1">
                    <img src="images/song/exit.jpg" alt="Image 2">
                    <img src="images/song/2340.jpg" alt="Image 3">
                    <img src="images/song/drt.jpg" alt="Image 4">

                    <img src="images/profile/hinh tao.jpg" alt="Image 1">
                    <img src="images/song/exit.jpg" alt="Image 2">
                    <img src="images/song/2340.jpg" alt="Image 3">
                    <img src="images/song/drt.jpg" alt="Image 4">
                </div>
            </div>


        </div>
        <div class="playlists">
            <h3>
                Playlists for you
            </h3>
            <div class="playlist">
                <img alt="Obito" height="150" src="https://placehold.co/50x50 " width="150" />
                <p>
                    Obito
                </p>
            </div>
            <div class="playlist">
                <img alt="" height="150" src="https://placehold.co/50x50 " width="150" />
                <p>
                    Tlinh
                </p>
            </div>
            <div class="playlist">
                <img alt="Gill" height="150" src="https://placehold.co/50x50 " width="150" />
                <p>
                    Gill
                </p>
            </div>
            <div class="playlist">
                <img alt="HurtyKng" height="150" src="https://placehold.co/50x50 " width="150" />
                <p>
                    HurtyKng
                </p>
            </div>
            <div class="playlist">
                <img alt="HIEUTHUHAI" height="150" src="https://placehold.co/50x50 " width="150" />
                <p>
                    HIEUTHUHAI
                </p>
            </div>
        </div>
        <h3>
            Recently played
        </h3>
        <div class="recently-played">
            <div class="song">
                <img alt="Chàng Là Gì" height="50"
                    src="https://storage.googleapis.com/a1aa/image/w9GelBJsr7TcVC4Gt32nlfpDmNMDUTffbeeijznpSo6Mll28E.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        Chàng Là Gì
                    </p>
                    <p>
                        SVAN - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="Cố mày" height="50"
                    src="https://storage.googleapis.com/a1aa/image/u1cuhTDSc4p3PxpvihE1zsBGmNVafHgQTu6vjZCZKL08Kt5JA.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        Cố mày
                    </p>
                    <p>
                        Hào - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="Sorry" height="50"
                    src="https://storage.googleapis.com/a1aa/image/85FHVu7VkMaEKxyHJXfZAWtfCO8oa4U3zO8ZYcN2bnK2VazTA.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        Sorry
                    </p>
                    <p>
                        Milly và Passed - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="Chàng Là Gì" height="50"
                    src="https://storage.googleapis.com/a1aa/image/w9GelBJsr7TcVC4Gt32nlfpDmNMDUTffbeeijznpSo6Mll28E.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        Chàng Là Gì
                    </p>
                    <p>
                        SVAN - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="MD Anniversary" height="50"
                    src="https://storage.googleapis.com/a1aa/image/hqu0x1hNYE7fAajwuhkYn0k8ddL8NS95FFFuY6lksQaFLt5JA.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        MD Anniversary
                    </p>
                    <p>
                        BAN và Coolkid - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="Để quên em" height="50"
                    src="https://storage.googleapis.com/a1aa/image/M1MCH07tEs5jJBn6NY8vajU6xBzlGx00FG0auFbVmk6gl28E.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        Để quên em
                    </p>
                    <p>
                        Flaky - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="Sỉ mê" height="50"
                    src="https://storage.googleapis.com/a1aa/image/3uVV6yKHNRJvN16MZozsRfelbDnnqe6QJMkENxooFMocs0mnA.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        Sỉ mê
                    </p>
                    <p>
                        TLR - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="1/2" height="50"
                    src="https://storage.googleapis.com/a1aa/image/0VULnDrNTF4UEF4FSiuHXUrbKsIJacZkKLkpozelj9KDLt5JA.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        1/2
                    </p>
                    <p>
                        DangRangTo - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="CANXA" height="50"
                    src="https://storage.googleapis.com/a1aa/image/hLmf1wjGeGkzG0060NcXyrENM3NicbA7hzhblrfUViwCs0mnA.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        CANXA
                    </p>
                    <p>
                        1DEE và FEEZY - 3:39
                    </p>
                </div>
            </div>
            <div class="song">
                <img alt="1000 Ánh Mắt" height="50"
                    src="https://storage.googleapis.com/a1aa/image/6bxvUtb3y2ZCOJovrYZYo1JUEuOsKQDFVHkDPoPxXJhkl28E.jpg"
                    width="50" />
                <div class="info">
                    <p>
                        1000 Ánh Mắt
                    </p>
                    <p>
                        Shiki và Obito - 3:39
                    </p>
                </div>
            </div>
        </div>
        <div class="trending-music">
            <h3>
                Trending Music
            </h3>
            <div class="music">
                <img alt="Hip Hop &amp; Rap" height="150"
                    src="https://storage.googleapis.com/a1aa/image/BZJVjfNwCDUXeUwS6MqCRnWtnKIiYWybAdo1FgkLRHnFWazTA.jpg"
                    width="150" />
                <p>
                    Hip Hop &amp; Rap
                </p>
                <p>
                    Trending Music
                </p>
            </div>
            <div class="music">
                <img alt="Jazz" height="150"
                    src="https://storage.googleapis.com/a1aa/image/qB8Kzw1Wvio8BdAkP3GkPvejNGEr1Jdk1GS1JJyiOe9XWazTA.jpg"
                    width="150" />
                <p>
                    Jazz
                </p>
                <p>
                    Trending Music
                </p>
            </div>
            <div class="music">
                <img alt="R&amp;B" height="150"
                    src="https://storage.googleapis.com/a1aa/image/pqJCawbHDPoyAJrjvOT5jNzmTWKbzWpij759ll0JexdLLt5JA.jpg"
                    width="150" />
                <p>
                    R&amp;B
                </p>
                <p>
                    Trending Music
                </p>
            </div>
            <div class="music">
                <img alt="Chill" height="150"
                    src="https://storage.googleapis.com/a1aa/image/BaJhoZpKJcIWIBTeNo8julXWiwTtRM691vpWSIqgtx6fVazTA.jpg"
                    width="150" />
                <p>
                    Chill
                </p>
                <p>
                    Trending Music
                </p>
            </div>
        </div>
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
            <!-- Dấu ba chấm (More Options) -->
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

    <!-- Overlay Login Form -->
    <div id="loginOverlay" class="overlay" style="display: none;">
        <div class="login-form">
            <span onclick="closeOverlay()"
                style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
            <h2>Welcome</h2>
            <h3>Login into your account</h3>

            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}"
                onclick="window.location.href='{{ route('login-google') }}'" alt="gg">

            <div class="separator">
                <hr> <span>Or continue with</span>
                <hr>
            </div>
            @if (session('message'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showLoginForm(); // Hiển thị form đăng nhập
                });
            </script>
            <div class="alert alert-danger" id="login-message">{{ session('message') }}</div>
            @endif
            <form id="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <input type="text" placeholder="Email" @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}">
                @error('email')
                <p class="invalid-feedback">{{ $message }}</p>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showLoginForm(); // Hiển thị form đăng nhập
                    });
                </script>
                @enderror
                <input type="password" placeholder="Password" @error('password') is-invalid @enderror" id="password"
                    name="password" value="{{ old('password') }}">
                @error('password')
                <p class="invalid-feedback">{{ $message }}</p>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showLoginForm(); // Hiển thị form đăng nhập
                    });
                </script>
                @enderror


                <div class="options">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" class="recover-password">Forgot Password?</a>
                </div>
                <button class="action-btn" type="submit">Log In</button>
            </form>
            <p>
                Don't have an account yet?
                <a href="javascript:void(0)" onclick="showRegisterForm()">Register</a>
            </p>

        </div>
    </div>
    <div id="registerOverlay" class="overlay" style="display: none;">

        <div class="form-container">
            <span onclick="closeOverlay()"
                style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
            <h2>Create Account</h2>
            <p>Register a new account</p>
            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}" onclick="" alt="gg">

            <div class="separator">
                <hr> <span>Or continue with</span>
                <hr>
            </div>

            <!-- Form đăng ký -->
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

                <button class="action-btn" type="submit">Register</button>
            </form>

            <p>
                Already have an account?
                <a onclick="showLoginForm()">Login</a>
            </p>
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