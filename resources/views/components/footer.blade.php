<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/style.css')
    <style>
        .footer {
            position: fixed;
            bottom: 5px;
            height: 7rem;
            z-index: 100;
        }
        .footer img{
            margin-left: 2rem;
        }
    </style>
</head>
<div class="footer" id="footer" style="display: none;">
    <img id="footerSongImg" src="" alt="Music Image" width="100" height="100">
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
        <p id="footerSongTitle">Tên bài hát</p>
        <p id="footerSongArtist">Tên ca sĩ</p>
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
    <audio id="footerAudioPlayer" src="" preload="auto" style="display:none;" controls></audio>
</div>
<script>
    // Lấy đối tượng audio và thanh tiến trình
    const audioPlayer = document.getElementById('footerAudioPlayer');
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
        const duration = audioPlayer.duration ? audioPlayer.duration : 0;

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
        const audioPlayer = document.getElementById('footerAudioPlayer');
        audioPlayer.src = `/audio/${filePath}`;
        audioPlayer.play();
    }
</script>