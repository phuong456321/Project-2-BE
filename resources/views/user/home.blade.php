<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music</title>
    @vite('resources/css/style.css')
    @vite('resources/css/nowPlay.css')
    @vite('resources/css/sideBarStyle.css')
    @vite('resources/css/audio.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src=""></script>




    <script>
        function showLoginForm() {
            document.getElementById("registerOverlay").style.display = "none"; // Ẩn form register
            document.getElementById("loginOverlay").style.display = "flex";
        }

        function showRegisterForm() {
            // console.log("showRegisterForm called");
            document.getElementById("loginOverlay").style.display = "none"; // Ẩn form login
            document.getElementById("registerOverlay").style.display = "flex";
        }

        function closeOverlay() {
            document.getElementById("overlay").style.display = "none"; // Ẩn overlay
        }

        function showLoginOverlay() {
            document.getElementById("overlay").style.display = "flex";
            showLoginForm(); // Mặc định hiển thị form login
        }


        // Đóng form khi nhấn ra ngoài
        window.onclick = function(event) {
            const overlay = document.getElementById("loginOverlay");
            if (event.target === overlay) {
                overlay.style.display = "none";
            } else if (event.target === document.getElementById("registerOverlay")) {
                document.getElementById("registerOverlay").style.display = "none";
            }
        };
        document.addEventListener('DOMContentLoaded', () => {
            const nowPlaying = document.querySelector('.now-playing');

            const toggleBtn = document.createElement('div');

            // Tạo nút toggle
            toggleBtn.className = 'toggle-btn';
            toggleBtn.innerHTML = '<span>&#x25C0;</span>'; // Mũi tên
            nowPlaying.parentElement.appendChild(toggleBtn);

            // Xử lý sự kiện click
            toggleBtn.addEventListener('click', () => {
                nowPlaying.classList.toggle('active'); // Thêm/xóa lớp active
                const arrow = toggleBtn.querySelector('span');
                if (nowPlaying.classList.contains('active')) {
                    arrow.innerHTML = '&#x25C0;'; // Mũi tên sang trái
                } else {
                    arrow.innerHTML = '&#x25B6;'; // Mũi tên sang phải
                }
            });
        });
        // const toggleButton = document.querySelector('.arrow-toggle');
        // toggleButton.addEventListener('click', () => {
        //     nowPlaying.classList.toggle('hidden'); // Ẩn/Hiện thẻ now-playing
        // });
    </script>

</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo">

            </div>
            <nav class="menu">
                <ul>
                    <li class="{{ request()->is('home') ? 'active' : '' }}">
                        <img src="{{ asset('icon/home-svgrepo-com.svg') }}" class="menu-icon">
                        <a href="{{ route('home') }}" id="home"> Home </a>
                    </li>
                    <li class="{{ request()->is('library') ? 'active' : '' }}">
                        <img src="{{ asset('icon/discover-home.svg') }}" class="menu-icon">
                        <a href="/discover" id="Discover"> Discover </a>
                    </li>
                    <li>
                        <img src="{{ asset('icon/library-home.svg') }}" class="menu-icon">
                        <a href="/library" id="Librarys"> Librarys </a>
                    </li>
                    <li>
                        <img src="icon/profile-home.svg" class="menu-icon">
                        <a href="/profile" id="profile" class="profile-btn w3-magin-bottom"> Profile </a>
                    </li>
                </ul>
            </nav>


            <hr>

            <div class="my-music">
                <Button>Create playlist</Button>
                <!-- <ul>
                    <li>Liked songs</li>
                    <li>Albums</li>
                    <li>Playlists1</li>
                    <li>Playlists2</li>
                </ul> -->
            </div>
            <div class="settings">
                <ul>
                    <li>Setting</li>
                    <li>Log out</li>
                </ul>
            </div>
        </aside>

        <main class="content">
            <div class="search-section">

                <div class="search-container">
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
            </div>

            <div class="auth-links">
                <a href="javascript:void(0)" onclick="showLoginForm()" class="login-link">Login</a>
                <span class="separator">/</span>
                <a href="javascript:void(0)" onclick="showRegisterForm()" class="register-link">Register</a>
            </div>



            <div class="main-section">
                <h1>Prevalent</h1>
                <div class="featured-artist">
                    <div class="featured-info">
                        <h2>HIEUTHUHAI</h>
                            <p>Ai Cũng Bắt Đầu Từ Đâu Đó!!</p>
                            <button>Listen now</button>
                    </div>
                    <div class="featured-image">
                        <img src="{{ asset('images/song/Hht.png') }}" alt="HIEUTHUHAI">
                    </div>
                </div>

                <div class="playlists">
                    <h3>Playlists for you</h3>
                    <!-- <div class="playlist-grid">
                        <div class="playlist-card">Obito</div>
                        <div class="playlist-card">DRT</div>
                        <div class="playlist-card">Tlinh</div>
                        <div class="playlist-card">VŨ</div>
                        <div class="playlist-card">WN</div>
                    </div> -->
                </div>


                <div id="recently">
                    <h3>Recently played</h3>
                </div>
                <div class="music-list">

                    <div class="music-item">
                        <img src="{{ asset('images/song/2340.jpg') }}" alt="23:40">
                        <div class="song-info">
                            <p class="song-title">23:40</p>
                            <p class="artist">Hào • 23:40</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/bầu trời.jpg') }}" alt="Bầu trời mới">
                        <div class="song-info">
                            <p class="song-title">Bầu Trời Mới (cùng với Minh Tốc & Lam)</p>
                            <p class="artist">Da LAB • Bầu Trời Mới</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/dấu chân.jpg') }}" alt="Dấu chân">
                        <div class="song-info">
                            <p class="song-title">Dấu chân (cùng với LePhuong)</p>
                            <p class="artist">Maize • Dấu chân</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/4getu.jpg') }}" alt="4getu">
                        <div class="song-info">
                            <p class="song-title">4GetU (Remake)</p>
                            <p class="artist">PC và Specter • 4GetU</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/exit.jpg') }}" alt="Exit Sign">
                        <div class="song-info">
                            <p class="song-title">Exit Sign</p>
                            <p class="artist">HIEUTHUHAI • Exit Sign</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/vết thương.jpg') }}" alt="Vết thương">
                        <div class="song-info">
                            <p class="song-title">Vết thương</p>
                            <p class="artist">Fishy • Vết thương</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/sóng.jpg') }}" alt="Sóng">
                        <div class="song-info">
                            <p class="song-title">Sóng</p>
                            <p class="artist">NGUYENN • Sóng</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/tiec') }}" alt="Tiếc làm chi">
                        <div class="song-info">
                            <p class="song-title">TIẾC LÀM GÌ?</p>
                            <p class="artist">PARYS • TIẾC LÀM GÌ?</p>
                        </div>
                    </div>

                    <div class="music-item">
                        <img src="{{ asset('images/song/GIẢ VỜ') }}" alt="Giả vờ">
                        <div class="song-info">
                            <p class="song-title">Giả vờ</p>
                            <p class="artist">DuongG • Giả vờ</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <aside class="now-playing">
            <div class="user-profile">
                <span>Nhóm 2</span>
                <img src="{{ asset('images/song/drt.jpg') }}" alt="Avatar" class="avatar">
            </div>
            <div class="now-playing-list">
                <h3>Now Playing</h3>
                <!-- <ul>
                    <li>
                        <img
                            src="{{ asset('images/song/Obito.jpg') }}" class="song-image">
                        <span class="song-duration"></span>
                    </li> 
                    <li>
                        <img src="{{ asset('images/song/drt.jpg') }}" class="song-image">
                      
                        <span class="song-duration"></span>
                    </li>
                    <li>
                        <img src="./img/images/song/exit.jpg" class="song-image">
                      
                        <span class="song-duration"></span>
                    </li>
                    <li>
                        <img src="./img/images/song/exit.jpg" class="song-image">
                     
                        <span class="song-duration"></span>
                    </li>
                    <li>
                        <img src="./img/images/song/exit.jpg" class="song-image">
                        
                        <span class="song-duration"></span>
                    </li>
                    <li>
                        <img src="./img/images/song/exit.jpg" class="song-image">
                      
                        <span class="song-duration"></span>
                    </li>

                </ul> -->
            </div>
        </aside>
        <span class="toggle-btn">&#x25C0;</span>
    </div>
    <div class="music-player">
        <div class="player-controls">
            <!-- Các nút chức năng -->
            <div class="controls">
                <button class="control-btn"><i class="fas fa-sync-alt"></i></button> <!-- Lặp lại -->
                <button class="control-btn"><i class="fas fa-step-backward"></i></button> <!-- Bài trước -->
                <button class="control-btn play-pause"><i class="fas fa-play"></i></button> <!-- Phát/Tạm dừng -->
                <button class="control-btn"><i class="fas fa-step-forward"></i></button> <!-- Bài sau -->
                <button class="control-btn"><i class="fas fa-random"></i></button> <!-- Phát ngẫu nhiên -->
            </div>

            <!-- Thanh tiến trình -->
            <div class="progress-bar">
                <span class="current-time">0:00</span>
                <input type="range" class="seek-bar" value="0" max="100" />
                <span class="total-time">3:41</span>
            </div>
        </div>

        <!-- Âm lượng -->
        <div class="volume">
            <i class="fas fa-volume-up"></i>
            <input type="range" class="volume-bar" value="80" max="100" />
        </div>
    </div>


    <!-- Overlay Login Form -->
    <div id="loginOverlay" class="overlay">
        <div class="login-form">
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

    <div class="container mt-3">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>


</body>

</html>