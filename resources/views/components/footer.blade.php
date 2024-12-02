<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>
    <style>
        .footer img {
            position: absolute;
            margin-left: 2rem;
        }

        .footer .current-song {
            margin-right: 0;
        }
    </style>
</head>
<div class="footer" id="footer" style="display: none;" data-song-id="">
    <div class="loader">
        <div class="justify-content-center jimu-primary-loading"></div>
    </div>
    <img id="footerSongImg" src="" alt="Music Image" width="90" height="90">
    <div class="controls">
        <i class="fas fa-step-backward"></i>
        <i class="fas fa-play" onclick="togglePlay()"></i>
        <i class="fas fa-step-forward" onclick=""></i>
    </div>
    <div class="progress">
        <input type="range" id="progressBar" max="100" min="0" value="0"
            oninput="changeProgress(this)" />
        <p id="currentTime">0:00</p> / <p id="totalTime">0:00</p>
    </div>
    <div class="current-song">
        <p id="footerSongTitle">Tên bài hát</p>
        <p id="footerSongArtist">Tên ca sĩ</p>
    </div>
    <div class="actions">
        <i class="fas fa-heart"></i>
        <i class="fas fa-random"></i>
        <i class="fas fa-volume-up"></i>
        <i class="fa-solid fa-music" id="toggleLyricsIcon"></i>
        <i onclick="openPopup()" class="fas fa-ellipsis-h"></i>
    </div>
    <!-- Audio element -->
    <audio id="footerAudioPlayer" style="display:none;" controls></audio>
</div>
<div id="adPopup" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
    <div class="!bg-white !p-6 !rounded-lg !w-4/5 !max-w-md !text-center">
        <!-- Quảng cáo AdSense -->
        <div class="popup-content">
            <span id="closePopup" class="close-btn">&times;</span>
            <!-- Chèn mã quảng cáo Google AdSense -->
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4089886839959004"
        crossorigin="anonymous"></script>
            <!-- Nulltifly-home -->
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-4089886839959004"
                data-ad-slot="5674451393"
                data-ad-format="auto"
                data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>

        <button onclick="closeAdPopup()" 
            class="px-6 py-2 bg-green-500 text-white font-medium rounded-md hover:bg-green-600 transition">
            Tiếp tục
        </button>
    </div>
</div>


<script>
    const user = {
        isLoggedIn: {{ Auth::check() ? 'true' : 'false' }},
        plan: "{{ Auth::check() ? Auth::user()->plan : 'guest' }}"
    };
    // Initialize Dash.js Player
    const footerAudioElement = document.getElementById('footerAudioPlayer');
    const player = dashjs.MediaPlayer().create();
    player.initialize(footerAudioElement, '', true); // Thiết lập player với element video/audio và để autoplay

    const progressBar = document.getElementById('progressBar');
    const currentTimeDisplay = document.getElementById('currentTime');
    const totalTimeDisplay = document.getElementById('totalTime');

    // Hàm bắt lỗi
    player.on('error', function(event) {
        console.error('Dash.js Error:', event.data);
    });

    function togglePlay() {
        if (footerAudioElement.paused) {
            footerAudioElement.play();
            document.querySelector('.fa-play').classList.replace('fa-play', 'fa-pause');
        } else {
            footerAudioElement.pause();
            document.querySelector('.fa-pause').classList.replace('fa-pause', 'fa-play');
        }
    }

    function changeProgress(progressBar) {
        const newTime = (progressBar.value / 100) * footerAudioElement.duration;
        footerAudioElement.currentTime = newTime;
    }

    function formatTime(timeInSeconds) {
        const minutes = Math.floor(timeInSeconds / 60);
        const seconds = Math.floor(timeInSeconds % 60);
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }

    function playAudio(filePath) {
        if (!filePath) {
            console.error('Không có file để phát');
            return;
        }
        player.attachSource(filePath); // Đính kèm nguồn DASH (MPD   file)
        footerAudioElement.play();
    }

    function playAudioWithAd(filePath) {
        if ((user.isLoggedIn && user.plan === 'free') || !user.isLoggedIn) {
            // Hiển thị popup quảng cáo trước khi phát
            showAdPopup(() => {
                playAudio(filePath); // Gọi hàm playAudio sau khi tắt popup
            });
        } else {
            playAudio(filePath); // Người dùng có gói Premium
        }
    }

    function showAdPopup(callback) {
        const popup = document.getElementById('adPopup');
        popup.style.display = 'flex';

        // Đóng popup và thực hiện callback
        window.closeAdPopup = function() {
            popup.style.display = 'none';
            if (typeof callback === 'function') callback();
        };
    }


    footerAudioElement.addEventListener('canplay', function() {
        // Đăng ký sự kiện `timeupdate`
        footerAudioElement.addEventListener('timeupdate', function() {
            const currentTime = footerAudioElement.currentTime;
            const duration = footerAudioElement.duration;

            // Kiểm tra xem currentTime và duration có hợp lệ không
            if (!isNaN(currentTime) && !isNaN(duration)) {
                currentTimeDisplay.textContent = formatTime(currentTime);
                totalTimeDisplay.textContent = formatTime(duration);

                // Cập nhật thanh tiến trình
                progressBar.value = (currentTime / duration) * 100 || 0;
            }
        });
    });
</script>
