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
    @vite('resources/js/play.js')
    <style>
        .search-form input[type="text"] {
            height: 2rem;
        }

        .search-song-icon i {
            position: relative;
            left: 0;
            top: 0;
            font-size: 20px;
            color: #f5efef;
            cursor: pointer;
        }

        .search-song-icon {
            left: -3rem;
        }

        .search-form input[type="text"] {
            height: 2.5rem;
        }
    </style>
</head>
@extends('user.layout')
@section('content')
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
    <div class="playlists">
        @if (count($playlists) > 0)
            <h3>
                Playlists for you
            </h3>
            @foreach ($playlists as $playlist)
                <div class="playlist cursor-pointer">
                    <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}"
                        class="flex flex-col items-center text-white no-underline">
                        <div class="images">
                            @if ($playlist->songs->isEmpty())
                                <img src="http://localhost:8000/images/profile/logo-home.png" alt="Default Image"
                                    class="song-image">
                            @else
                                @foreach ($playlist->songs as $song)
                                    <img src="{{ url('image/' . $song->img_id) }}" alt="Song Image" class="song-image">
                                @endforeach
                            @endif
                        </div>
                        <h3>{{ $playlist->name }}</h3>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
    {{-- <h3>
        Recently played
    </h3>
    <div class="recently-played">
        <div class="song">
            <img alt="Chàng Là Gì" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="Cố mày" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="Sorry" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="Chàng Là Gì" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="MD Anniversary" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="Để quên em" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="Sỉ mê" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="1/2" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="CANXA" height="50" src="https://placehold.co/50x50" width="50" />
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
            <img alt="1000 Ánh Mắt" height="50" src="https://placehold.co/50x50" width="50" />
            <div class="info">
                <p>
                    1000 Ánh Mắt
                </p>
                <p>
                    Shiki và Obito - 3:39
                </p>
            </div>
        </div>
    </div> --}}
    <div class="trending-music">
        <h3>
            Trending Music
        </h3>
        @foreach ($songs as $song)
            <div class="music song-item max-h-[200px] min-h-[200px] overflow-hidden cursor-pointer"
                data-song-id="{{ $song->id }}">
                <img alt="{{ $song->song_name }}" height="150" src="{{ url('image/' . $song->img_id) }}" width="150" />
                <p id="song-name">
                    {{ $song->song_name }}
                </p>
                <p id="song-artist">
                    {{ $song->author->author_name }}
                </p>
                <audio src="{{ url('storage/' . $song->audio_path) }}" preload="auto" style="display:none;"
                    controls></audio>
                <p id="lyrics-text" class="whitespace-pre-line"> {{ $song->lyric }} </p>
            </div>
        @endforeach
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
                    <input type="checkbox" name="remember" id="remember"> Remember me
                </label>
                <a href="{{ route('password.forgot') }}" class="recover-password">Forgot Password?</a>
            </div>
            <button class="action-btn" type="submit">Log In</button>
        </form>
        <p>
            Don't have an account yet?
            <a href="javascript:void(0)" onclick="showRegisterForm()">Register</a>
        </p>

    </div>
@endsection
@extends('components.footer')
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
    //add song
    // Open popup
    function openPopup() {
        document.getElementById('overlay').classList.add('active');
    }

    // Close popup
    function closePopup() {
        document.getElementById('overlay').classList.remove('active');
    }
    let selectedPlaylists = []; // Lưu các playlist_id đã chọn
</script>

</html>