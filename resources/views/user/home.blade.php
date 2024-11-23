<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @vite('resources/css/style.css')

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
        window.onclick = function(event) {
            if (event.target === document.getElementById("loginOverlay")) {
                closeOverlay();
            } else if (event.target === document.getElementById("registerOverlay")) {
                closeOverlay();
            }
        };
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
            <input placeholder="Bạn đang tìm kiếm gì?" type="text" />

            @if(Auth::check())
            {{-- Nếu người dùng đã đăng nhập --}}
            <div id="avatar" class="user" onclick="Popup()">
                <span>{{ Auth::user()->name }}</span>
                <img alt="User Avatar" class="rounded-full"
                    height="40"
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.jpg') }}"
                    width="40" />
            </div>

            <!-- Popup Profile / Logout -->
            <div id="popup" class="avatar-popup hidden">
                <ul>
                    <li><a href="{{ route('profile') }}">Profile</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
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
            <img alt="Prevalent artist" height="150" src="images/song/exit.jpg" width="150" />
        </div>
        <div class="playlists">
            <h3>
                Playlists for you
            </h3>
            <div class="playlist">
                <img alt="Obito" height="150" src="images/song/Obito.jpg" width="150" />
                <p>
                    Obito
                </p>
            </div>
            <div class="playlist">
                <img alt="Tlinh" height="150" src="images/song/tlinh.jpg" width="150" />
                <p>
                    Tlinh
                </p>
            </div>
            <div class="playlist">
                <img alt="Gill" height="150" src="images/song/drt.jpg" width="150" />
                <p>
                    Gill
                </p>
            </div>
            <div class="playlist">
                <img alt="HurtyKng" height="150" src="images/song/tiec.jpg" width="150" />
                <p>
                    HurtyKng
                </p>
            </div>
            <div class="playlist">
                <img alt="HIEUTHUHAI" height="150" src="images/song/wn.jpg" width="150" />
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
                <img alt="Chàng Là Gì" height="50" src="https://storage.googleapis.com/a1aa/image/w9GelBJsr7TcVC4Gt32nlfpDmNMDUTffbeeijznpSo6Mll28E.jpg" width="50" />
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
                <img alt="Cố mày" height="50" src="https://storage.googleapis.com/a1aa/image/u1cuhTDSc4p3PxpvihE1zsBGmNVafHgQTu6vjZCZKL08Kt5JA.jpg" width="50" />
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
                <img alt="Sorry" height="50" src="https://storage.googleapis.com/a1aa/image/85FHVu7VkMaEKxyHJXfZAWtfCO8oa4U3zO8ZYcN2bnK2VazTA.jpg" width="50" />
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
                <img alt="Chàng Là Gì" height="50" src="https://storage.googleapis.com/a1aa/image/w9GelBJsr7TcVC4Gt32nlfpDmNMDUTffbeeijznpSo6Mll28E.jpg" width="50" />
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
                <img alt="MD Anniversary" height="50" src="https://storage.googleapis.com/a1aa/image/hqu0x1hNYE7fAajwuhkYn0k8ddL8NS95FFFuY6lksQaFLt5JA.jpg" width="50" />
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
                <img alt="Để quên em" height="50" src="https://storage.googleapis.com/a1aa/image/M1MCH07tEs5jJBn6NY8vajU6xBzlGx00FG0auFbVmk6gl28E.jpg" width="50" />
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
                <img alt="Sỉ mê" height="50" src="https://storage.googleapis.com/a1aa/image/3uVV6yKHNRJvN16MZozsRfelbDnnqe6QJMkENxooFMocs0mnA.jpg" width="50" />
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
                <img alt="1/2" height="50" src="https://storage.googleapis.com/a1aa/image/0VULnDrNTF4UEF4FSiuHXUrbKsIJacZkKLkpozelj9KDLt5JA.jpg" width="50" />
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
                <img alt="CANXA" height="50" src="https://storage.googleapis.com/a1aa/image/hLmf1wjGeGkzG0060NcXyrENM3NicbA7hzhblrfUViwCs0mnA.jpg" width="50" />
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
                <img alt="1000 Ánh Mắt" height="50" src="https://storage.googleapis.com/a1aa/image/6bxvUtb3y2ZCOJovrYZYo1JUEuOsKQDFVHkDPoPxXJhkl28E.jpg" width="50" />
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
                <img alt="Hip Hop &amp; Rap" height="150" src="https://storage.googleapis.com/a1aa/image/BZJVjfNwCDUXeUwS6MqCRnWtnKIiYWybAdo1FgkLRHnFWazTA.jpg" width="150" />
                <p>
                    Hip Hop &amp; Rap
                </p>
                <p>
                    Trending Music
                </p>
            </div>
            <div class="music">
                <img alt="Jazz" height="150" src="https://storage.googleapis.com/a1aa/image/qB8Kzw1Wvio8BdAkP3GkPvejNGEr1Jdk1GS1JJyiOe9XWazTA.jpg" width="150" />
                <p>
                    Jazz
                </p>
                <p>
                    Trending Music
                </p>
            </div>
            <div class="music">
                <img alt="R&amp;B" height="150" src="https://storage.googleapis.com/a1aa/image/pqJCawbHDPoyAJrjvOT5jNzmTWKbzWpij759ll0JexdLLt5JA.jpg" width="150" />
                <p>
                    R&amp;B
                </p>
                <p>
                    Trending Music
                </p>
            </div>
            <div class="music">
                <img alt="Chill" height="150" src="https://storage.googleapis.com/a1aa/image/BaJhoZpKJcIWIBTeNo8julXWiwTtRM691vpWSIqgtx6fVazTA.jpg" width="150" />
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
        </div>
    </div>

    <!-- Overlay Login Form -->
    <div id="loginOverlay" class="overlay">
        <div class="login-form">
            <span onclick="closeOverlay()" style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
            <h2>Welcome</h2>
            <p>Login into your account</p>

            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}" onclick="" alt="gg">

            <div class="separator">
                <hr> <span>Or continue with</span>
                <hr>
            </div>

            <form action="" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <div class="options">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" class="recover-password">Forgot Password?</a>
                </div>
                <button class="action-btn" type="submit">Log In</button>
                <p>
                    Don't have an account yet?
                    <a href="javascript:void(0)" onclick="showRegisterForm()">Register</a>
                </p>
            </form>
        </div>
    </div>
    <div id="registerOverlay" class="overlay">

        <div class="form-container">
            <span onclick="closeOverlay()" style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
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
                <a href="javascript:void(0)" onclick="showLoginForm()">Login</a>
            </p>
        </div>
    </div>
</body>

</html>