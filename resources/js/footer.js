document.addEventListener('DOMContentLoaded', function () {
    isPlayingPlaylist = false;
    currentSongIndex = -1;
    // Lấy đối tượng audio và thanh tiến trình
    const audioPlayer = document.getElementById('footerAudioPlayer');
    const volumeSlider = document.getElementById('volumeSlider');
    const volumeIcon = document.getElementById('volumeIcon');

    // Cập nhật âm lượng khi kéo thanh trượt
    volumeSlider.addEventListener('input', function () {
        const volume = parseFloat(this.value);
        audioPlayer.volume = volume; // Gán âm lượng cho trình phát
        updateVolumeIcon(volume); // Cập nhật icon âm lượng
    });

    // Hàm cập nhật biểu tượng âm lượng
    function updateVolumeIcon(volume) {
        if (volume === 0) {
            volumeIcon.className = 'fas fa-volume-mute text-xl cursor-pointer';
        } else if (volume > 0 && volume <= 0.5) {
            volumeIcon.className = 'fas fa-volume-down text-xl cursor-pointer';
        } else {
            volumeIcon.className = 'fas fa-volume-up text-xl cursor-pointer';
        }
    }

    // Gán sự kiện click để bật/tắt tiếng
    volumeIcon.addEventListener('click', function () {
        if (audioPlayer.volume > 0) {
            audioPlayer.volume = 0;
            volumeSlider.value = 0; // Cập nhật giá trị của thanh trượt
            updateVolumeIcon(0);
        } else {
            audioPlayer.volume = 1;
            volumeSlider.value = 1;
            updateVolumeIcon(1);
        }
    });
});

// Initialize Dash.js Player
window.footerAudioElement = document.getElementById('footerAudioPlayer');
window.player = dashjs.MediaPlayer().create();
window.player.initialize(footerAudioElement, '/', true); // Thiết lập player với element video/audio và để autoplay

const progressBar = document.getElementById('progressBar');
const currentTimeDisplay = document.getElementById('currentTime');
const totalTimeDisplay = document.getElementById('totalTime');

// Hàm bắt lỗi
player.on('error', function (event) {
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
    const songId = document.getElementById('footer').dataset.songId;
    checkLikedStatus(songId);
    player.attachSource(filePath); // Đính kèm nguồn DASH (MPD   file)
    footerAudioElement.load();
    footerAudioElement.play();
    document.querySelector('.fa-play').classList.replace('fa-play', 'fa-pause');
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
    window.closeAdPopup = function () {
        popup.style.display = 'none';
        if (typeof callback === 'function') callback();
    };
}


footerAudioElement.addEventListener('canplay', function () {
    // Đăng ký sự kiện `timeupdate`
    footerAudioElement.addEventListener('timeupdate', function () {
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

function likeSong() { //Thêm bài hát vào likeplaylsit nếu người dùng đã đăng nhập
    const songId = document.getElementById('footer').dataset.songId;

    if (!user.isLoggedIn) {
        alert("Bạn cần đăng nhập để thích bài hát.");
        return;
    }

    // Gửi request lưu bài hát vào like playlist
    fetch(`/like-song`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            song_id: songId
        })
    }).then(response => response.json())
        .then(data => {
            checkLikedStatus(songId);
        })
        .catch(error => {
            console.log('Error:', error);
        });
}

function checkLikedStatus(songId) {
    if (!user.isLoggedIn) return;
    $.ajax({
        url: '/check-like',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        data: JSON.stringify({
            song_id: songId
        }),
        success: function (data) {
            const likeIcon = document.querySelector('.fas.fa-heart');
            if (data) {
                likeIcon.style.color = 'red'; // Bài hát đã được thích
            } else {
                likeIcon.style.color = '#FFF'; // Bài hát chưa được thích
            }
        },
        error: function (xhr, status, error) {
            console.error('Error loading authors:', error);
            console.error('Status:', status);
            console.error('Response Text:', xhr.responseText); // Hiển thị chi tiết nội dung response
        }
    });
};

// Di chuyển các hàm ra ngoài để có thể truy cập global
function playAllSongs() {
    const songs = window.playlistSongs;
    if (typeof songs !== 'undefined' && songs.length > 0) {
        isPlayingPlaylist = true;
        currentSongIndex = 0;
        playCurrentSong();
    } else {
        console.error('No songs available in playlist');
    }
}

function playCurrentSong() {
    if (!playlistSongs || playlistSongs.length === 0) return;

    const currentSong = playlistSongs[currentSongIndex];
    // Cập nhật thông tin footer player
    document.getElementById('footerSongImg').setAttribute('src', `/image/${currentSong.img_id}`);
    document.getElementById('footerSongTitle').innerText = currentSong.song_name;
    document.getElementById('footerSongArtist').innerText = currentSong.author_name;
    document.getElementById('footer-lyrics-text').innerText = currentSong.lyric || '';
    document.getElementById('footer-lyrics-img').setAttribute('src', `/image/${currentSong.img_id}`);
    document.getElementById('footer').setAttribute('data-song-id', currentSong.id);
    document.getElementById('footerAudioPlayer').setAttribute('src', `/storage/${currentSong.audio_path}`);

    // Thêm bài hát vào lịch sử
    historySongs.push(currentSong);

    // Sử dụng đường dẫn đã được chuẩn hóa
    playAudioWithAd(`/storage/${currentSong.audio_path}`);

    document.getElementById('footer').style.display = 'flex';
}

function previousSong() {
    if (historySongs.length > 1) {
        // Loại bỏ bài hiện tại khỏi lịch sử
        historySongs.pop();

        // Lấy bài trước đó
        const previousSong = historySongs[historySongs.length - 1];
        updateFooterPlayer(previousSong);
        playAudioWithAd('storage/' + previousSong.audio_path);
    } else {
        flash('Không còn bài nào trước đó!', 'error');
    }
}


function nextSong() {
    if (isPlayingPlaylist && currentSongIndex < playlistSongs.length - 1) {
        currentSongIndex++;
        playCurrentSong();
    } else if (recommendedSongs.length > 0) {
        // Phát bài tiếp theo từ danh sách gợi ý
        const nextRecommendedSong = recommendedSongs.shift(); // Lấy bài đầu tiên và xóa khỏi danh sách
        playRecommendedSong(nextRecommendedSong);
    } else if (songs.length > 0) {
        // Phát bài tiếp theo từ danh sách bài hát
        const nextSong = songs.shift(); // Lấy bài đầu tiên và xóa khỏi danh sách
        playRecommendedSong(nextSong);
    }
}

function updateFooterPlayer(song) {
    document.getElementById('footerSongImg').setAttribute('src', song.img_id ? `/image/${song.img_id}` : '');
    document.getElementById('footerSongTitle').innerText = song.song_name;
    document.getElementById('footerSongArtist').innerText = song.author.author_name;
    document.getElementById('footer-lyrics-text').innerText = song.lyric || '';
    document.getElementById('footer-lyrics-img').setAttribute('src', song.img_id ? `/image/${song.img_id}` : '');
    document.getElementById('footer').setAttribute('data-song-id', song.id);
    document.getElementById('footerAudioPlayer').setAttribute('src', 'storage/' + song.audio_path);
}

function playRecommendedSong(recommendedSong) {
    updateFooterPlayer(recommendedSong);
    // Thêm bài hát gợi ý vào lịch sử
    historySongs.push(recommendedSong);

    playAudioWithAd('storage/' + recommendedSong.audio_path);
}
let selectedPlaylists = []; // Lưu các playlist_id đã chọn
let deleteSongFromPlaylist = [];
let historySongs = [];

