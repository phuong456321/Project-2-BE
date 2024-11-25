<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Music</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    @vite('resources/css/style.css')
</head>

<body>
    <div class="sidebar">
        <a class="no-hover" href="{{ route('home') }}">
            <img alt="Logo" height="100" src="images/profile/logo-home.png" width="100" />
        </a>
        <a href="{{ route('home') }}" id="home" class="{{ request()->is('home') ? 'active' : '' }}">
            Home
        </a>
        <a href="{{route('library')}}" id="librarys">
            Library
        </a>
        <a href="/playist" id="playist">
            Playist
        </a>
        <div class="create-playlist">
            + Create playlist
        </div>
        <a href="/likesong" id="likesong">
            Like songs
        </a>
        <a href="/albums" id="librarys">
            My Album
        </a>
        <a href="/playist" id="playist">
            Playlist 1
        </a>
    </div>
    <div class="main-content">
        <div class="header">
            <!-- Search -->
            <form action="{{ route('searchsong') }}" method="get" class="search-form">
            <input name="query" placeholder="Bạn đang tìm kiếm gì?" type="text" />
            <button type="submit" class="search-song-icon">
                <i class="fa-solid fa-magnifying-glass fa-lg"></i>
            </button>
        </form>
            @if (Auth::check())
                {{-- Nếu người dùng đã đăng nhập --}}
                <div id="avatar" class="user" onclick="togglePopup()">
                    <span>{{ Auth::user()->name }}</span>
                    <img alt="User Avatar" class="rounded-full" height="40"
                        src="{{ url('image/' . Auth::user()->avatar_id) }}"
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
                <a onclick="showLoginForm()" class="login-link">Login</a>
                <span class="separator">/</span>
                <a onclick="showRegisterForm()" class="register-link">Register</a>
            </div>
            @endif
        </div>
        <ul id="songList"></ul>
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
            <img alt="Prevalent artist" height="150" src="images/song/exit.jpg" width="150" />
        </div>
        <div class="playlists">
            <h3>
                Playlists for you
            </h3>
            @foreach ($authors as $author)
                <div class="playlist">
                    <img src="{{ url('image/' . $author->img_id) }}" alt="{{ App\Models\Image::where('img_id', $author->img_id)->first()->img_name }}" height="150" width="150">
                    <p>{{ $author->author_name }}</p>
                  </div>
            @endforeach
        </div>
        <h3>
            Recently played
        </h3>
        <div class="recently-played">
            <div class="song">
                <img alt="Chàng Là Gì" height="50"
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/50x50"
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
                    src="https://placehold.co/150x150"
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
                    src="https://placehold.co/150x150"
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
                    src="https://placehold.co/150x150"
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
                    src="https://placehold.co/150x150"
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
    {{-- <div class="footer">
        <img src="" alt="">
        <div class="controls">

            <i class="fas fa-step-backward">
            </i>
            <i class="fas fa-play" onclick="togglePlay()">
            </i>
            <i class="fas fa-step-forward">
            </i>
        </div>
        <!-- Display Current Time -->
    <div class="current-time">
        <span id="currentTime">0:00</span> / <span id="totalTime">0:00</span>
    </div>
        <div class="progress">
            <input type="range" id="progressBar" max="100" min="0" value="0"
                oninput="changeProgress(this)" />
        </div>
        <div class="current-song">
            <p id="currentSongTitle">CANXA</p>
            <p id="currentSongArtist">1DEE và FEEZY - 3:39</p>
        </div>
        <div class="actions">
            <i class="fas fa-heart">
            </i>
            <i class="fas fa-random">
            </i>
            <i class="fas fa-volume-up">
            </i>
        </div>
        <!-- Audio element -->
        <audio id="audioPlayer" src="audio/music/W9TTtw6VsZJ7E1NiT9S0H9UxWXiKYXuHPVwAzqeo.mp3"
            preload="auto" style="display:none;" controls></audio>
    </div> --}}

    <!-- Overlay Login Form -->
    <div id="loginOverlay" class="overlay" style="display: none">
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
    <div id="registerOverlay" class="overlay" style="display: none">

        <div class="form-container">
            <span onclick="closeOverlay()"
                style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
            <h2>Create Account</h2>
            <p>Register a new account</p>
            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}" onclick="window.location.href='{{ route('login-google') }}'" alt="gg">

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
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Đảm bảo cả hai overlay đều ẩn khi tải trang
        document.getElementById("loginOverlay").style.display = "none";
        document.getElementById("registerOverlay").style.display = "none";

        // Xử lý avatar popup
        const avatar = document.querySelector('#avatar');
        const popup = document.querySelector('.avatar-popup');
        if (avatar && popup) {
            avatar.addEventListener('click', function(e) {
                e.stopPropagation(); // Ngăn chặn sự kiện ngoài để popup không đóng
                popup.classList.toggle('block');
            });

            // Ẩn popup khi click bên ngoài
            document.addEventListener('click', function(e) {
                if (!popup.contains(e.target) && !avatar.contains(e.target)) {
                    popup.classList.remove('block');
                }
            });

            popup.addEventListener('click', function(e) {
                e.stopPropagation(); // Ngăn chặn sự kiện bên trong popup
            });
        }
    });

    // Hiển thị form đăng nhập
    function showLoginForm() {
        document.getElementById("registerOverlay").style.display = "none"; // Ẩn form đăng ký
        document.getElementById("loginOverlay").style.display = "flex"; // Hiển thị form đăng nhập
    }

    // Hiển thị form đăng ký
    function showRegisterForm() {
        document.getElementById("loginOverlay").style.display = "none"; // Ẩn form đăng nhập
        document.getElementById("registerOverlay").style.display = "flex"; // Hiển thị form đăng ký
    }

    // Đóng các overlay
    function closeOverlay() {
        document.getElementById("loginOverlay").style.display = "none";
        document.getElementById("registerOverlay").style.display = "none";
    }

    // Đóng overlay khi nhấn ra ngoài
    window.onclick = function(event) {
        if (event.target === document.getElementById("loginOverlay") || event.target === document.getElementById(
                "registerOverlay")) {
            closeOverlay();
        }
    };

    // Lấy đối tượng audio và thanh tiến trình
    const audioPlayer = document.getElementById('audioPlayer');
    const progressBar = document.getElementById('progressBar');
    const currentTimeDisplay = document.getElementById('currentTime');
    const totalTimeDisplay = document.getElementById('totalTime');
    // Điều khiển phát/tạm dừng
    function togglePlay() {
        if (audioPlayer.paused) {
            audioPlayer.play(); // Phát nhạc
            document.querySelector('.fa-play').classList.replace('fa-play', 'fa-pause'); // Thay đổi biểu tượng
        } else {
            audioPlayer.pause(); // Tạm dừng nhạc
            document.querySelector('.fa-pause').classList.replace('fa-pause', 'fa-play'); // Thay đổi biểu tượng
        }
    }

    // Thay đổi tiến trình phát nhạc khi người dùng kéo thanh tiến trình
    function changeProgress(progressBar) {
        if (!audioPlayer.readyState) {
        alert('Tệp âm thanh chưa được tải đủ để tua. Vui lòng thử lại!');
            return;
        }
        const newTime = (progressBar.value / 100) * audioPlayer.duration;
        audioPlayer.currentTime = newTime; // Thay đổi currentTime trực tiếp
    }

    // Cập nhật tiến trình phát nhạc
    audioPlayer.addEventListener('timeupdate', () => {
         // Tính thời gian hiện tại và thời gian tổng
    const currentTime = audioPlayer.currentTime;
    const duration = audioPlayer.duration;

    // Chuyển thời gian từ giây sang phút:giây (ví dụ: 2:15)
    const formatTime = (timeInSeconds) => {
        const minutes = Math.floor(timeInSeconds / 60);
        const seconds = Math.floor(timeInSeconds % 60);
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    };

    // Cập nhật thời gian hiện tại và tổng thời gian
    currentTimeDisplay.textContent = formatTime(currentTime);
    totalTimeDisplay.textContent = formatTime(duration);

    // Cập nhật thanh tiến trình
    const progressPercent = (currentTime / duration) * 100 || 0;
    progressBar.value = progressPercent;
    });

function playAudio(filePath) {
    const audioPlayer = document.getElementById('audioPlayer');
    audioPlayer.src = `/audio/${filePath}`;
    audioPlayer.play();
}




</script>

</html>
