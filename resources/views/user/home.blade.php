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
    </style>
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
                    10); // Thêm chút độ trễ nhỏ để cho phép class 'hidden' thay đổi trước khi 'show' được áp dụng
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
        @foreach ($authors as $author)
            <div class="playlist">
                <img src="{{ url('image/' . $author->img_id) }}"
                    alt="{{ App\Models\Image::where('img_id', $author->img_id)->first()->img_name }}"
                    style="height: 150px; width: 150px;">
                <p>{{ $author->author_name }}</p>
            </div>
        @endforeach
    </div>
    <h3>
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
    </div>
    <div class="trending-music">
        <h3>
            Trending Music
        </h3>
        <div class="music">
            <img alt="Hip Hop &amp; Rap" height="150" src="https://placehold.co/150x150" width="150" />
            <p>
                Hip Hop &amp; Rap
            </p>
            <p>
                Trending Music
            </p>
        </div>
        <div class="music">
            <img alt="Jazz" height="150" src="https://placehold.co/150x150" width="150" />
            <p>
                Jazz
            </p>
            <p>
                Trending Music
            </p>
        </div>
        <div class="music">
            <img alt="R&amp;B" height="150" src="https://placehold.co/150x150" width="150" />
            <p>
                R&amp;B
            </p>
            <p>
                Trending Music
            </p>
        </div>
        <div class="music">
            <img alt="Chill" height="150" src="https://placehold.co/150x150" width="150" />
            <p>
                Chill
            </p>
            <p>
                Trending Music
            </p>
        </div>
    </div>
    <!-- Popup lyrics -->
    <div id="lyricPopup" class="popup-lyrics hidden">
        <div class="popup-lyrics-content">
            <div class="lyrics-container">
                <div class="left">
                    <img alt="Album cover with text 'SƠN TÙNG M-TP CÓ CHẮC YÊU LÀ ĐÂY' and a person looking down"
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
        <!-- Audio element -->
        <audio id="audioPlayer" src="audio/music/W9TTtw6VsZJ7E1NiT9S0H9UxWXiKYXuHPVwAzqeo.mp3" preload="auto"
            style="display:none;" controls></audio>
    </div> --}}

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
            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}"
                onclick="window.location.href='{{ route('login-google') }}'" alt="gg">

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
                <div class="flex items-center mb-4 cursor-pointer" onclick="toggleSelection(this)">
                    <img alt="Heart icon" class="w-6 h-6 rounded mr-3"
                        src="https://storage.googleapis.com/a1aa/image/B52gnTORR458O56CfplN0UUXr2vJMWPUie257n5NYDgJkH2TA.jpg"
                        width="24" height="24" />
                    <span class="flex-1 text-white">Bài hát đã thích</span>
                    <i class="fas fa-check-circle text-green-500 mr-2 hidden"></i>
                </div>
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
                <button class="bg-gray-700 text-gray-400 py-2 px-4 rounded-lg hover:bg-gray-600" onclick="closePopup()">
                    Hủy
                </button>
                <button class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600"
                    onclick="confirmSelection()">
                    Thêm
                </button>
            </div>
        </div>
    @endsection


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
