document.addEventListener('DOMContentLoaded', function () {
    window.isPlayingPlaylist = false; //Trạng thái phát playlist
    window.currentSongIndex = -1; //Vị trí bài đang phát trong playlist
    window.selectedPlaylists = []; // Lưu các playlist_id đã chọn
    window.deleteSongFromPlaylist = []; //Chứa các bài nhạc xóa khỏi playlist
    window.historySongs = []; //Chứa các bài nhạc đã nghe
    window.isRepeat = false;
    window.isRandom = false;
    window.topSongItem = document.querySelector('.top-song-item');

    //Lấy các phần tử cần thiết
    window.progressBar = document.getElementById('progressBar');
    window.currentTimeDisplay = document.getElementById('currentTime');
    window.totalTimeDisplay = document.getElementById('totalTime');

    // Lấy đối tượng audio và thanh tiến trình
    window.volumeSlider = document.getElementById('volumeSlider');
    window.volumeIcon = document.getElementById('volumeIcon');
    // Initialize Dash.js Player
    window.footerAudioElement = document.getElementById('footerAudioPlayer');
    const player = dashjs.MediaPlayer().create();
    window.player = player;
    player.initialize(footerAudioElement, '/', true);

    // Cập nhật âm lượng khi kéo thanh trượt
    volumeSlider.addEventListener('input', function () {
        const volume = parseFloat(this.value);
        footerAudioElement.volume = volume; // Gán âm lượng cho trình phát
        updateVolumeIcon(volume); // Cập nhật icon âm lượng
    });

    // Gán sự kiện click để bật/tắt tiếng
    volumeIcon.addEventListener('click', function () {
        if (footerAudioElement.volume > 0) {
            footerAudioElement.volume = 0;
            volumeSlider.value = 0; // Cập nhật giá trị của thanh trượt
            updateVolumeIcon(0);
        } else {
            footerAudioElement.volume = 1;
            volumeSlider.value = 1;
            updateVolumeIcon(1);
        }
    });

    footerAudioElement.addEventListener('canplay', function () {
        try {
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
        } catch (error) {
            console.error('Error updating time:', error);
        }
    });

    // Áp dụng sự kiện click cho tất cả các bài hát
    document.querySelectorAll('.song-item').forEach(item => {
        item.addEventListener('click', function () {
            handleSongClick(this);
        });
    });

    // Áp dụng sự kiện click cho bài hát trong mục "Kết quả hàng đầu"
    if (topSongItem) {
        topSongItem.addEventListener('click', function () {
            handleSongClick(this);
        });
    }

    // Thêm event listener cho khi bài hát kết thúc
    footerAudioElement.addEventListener('ended', function () {
        try {
            if (isRepeat) {
                // Nếu chế độ Repeat được bật, phát lại bài hát hiện tại
                footerAudioElement.currentTime = 0;
                setTimeout(() => {
                    footerAudioElement.play();
                }, 5);
                return;
            }

            if (isPlayingPlaylist) {
                if (currentSongIndex < playlistSongs.length) {
                    if (isRandom) {
                        // Nếu chế độ Random được bật, phát bài ngẫu nhiên
                        const randomIndex = Math.floor(Math.random() * playlistSongs.length);
                        currentSongIndex = randomIndex;
                        playCurrentSong();
                        return;
                    } else {
                        currentSongIndex++;
                        playCurrentSong();
                    }
                } else {
                    // Hết playlist
                    currentSongIndex = 0;
                    isPlayingPlaylist = false;
                }
            }
            else if (recommendedSongs.length > 0) {
                // Phát bài tiếp theo từ danh sách gợi ý
                const nextRecommendedSong = recommendedSongs.shift(); // Lấy bài đầu tiên và xóa khỏi danh sách
                playRecommendedSong(nextRecommendedSong);
            }
            else if (songs.length > 0) {
                // Phát bài tiếp theo từ danh sách bài hát
                const nextSong = songs.shift(); // Lấy bài đầu tiên và xóa khỏi danh sách
                playRecommendedSong(nextSong);
            }
        } catch (error) {
            console.error('Error playing next song:', error);
        }
    });

    window.addEventListener('beforeunload', function () {
        try {
            const footerSongImg = document.getElementById('footerSongImg').getAttribute('src');
            const footerSongTitle = document.getElementById('footerSongTitle').innerText;
            const footerSongArtist = document.getElementById('footerSongArtist').innerText;
            const footerLyricsText = document.getElementById('footer-lyrics-text').innerText;
            const footerLyricsImg = document.getElementById('footer-lyrics-img').getAttribute('src');
            const dataSongId = document.getElementById('footer').getAttribute('data-song-id');
            const currentTime = footerAudioElement.currentTime;
            const isPlaying = !footerAudioElement.paused;
            const audioPath = player.getSource();

            // Kiểm tra và gán mặc định nếu các biến playlist chưa tồn tại
            window.isPlayingPlaylist = typeof isPlayingPlaylist !== 'undefined' ? isPlayingPlaylist : false;
            window.playlistSongs = typeof playlistSongs !== 'undefined' ? playlistSongs : [];
            window.currentSongIndex = typeof currentSongIndex !== 'undefined' ? currentSongIndex : -1;

            // Lưu trạng thái playlist nếu đang phát playlist
            if (isPlayingPlaylist) {
                localStorage.setItem('playlistData', JSON.stringify({ playlistSongs, currentSongIndex }));
            }

            // Lưu trạng thái trình phát vào localStorage
            localStorage.setItem('playerState', JSON.stringify({
                dataSongId,
                footerSongImg,
                footerSongTitle,
                footerSongArtist,
                footerLyricsText,
                footerLyricsImg,
                currentTime,
                isPlaying,
                audioPath,
                isPlayingPlaylist
            }));
        } catch (error) {
            console.error('Error saving player state:', error);
        }
    });

    window.addEventListener('load', function () {
        try {
            const playerState = JSON.parse(localStorage.getItem('playerState'));

            if (playerState) {
                const {
                    dataSongId,
                    footerSongImg,
                    footerSongTitle,
                    footerSongArtist,
                    footerLyricsText,
                    footerLyricsImg,
                    currentTime,
                    isPlaying,
                    audioPath,
                    isPlayingPlaylist
                } = playerState;

                // Khôi phục bài hát
                if (dataSongId) {
                    // Khôi phục playlist nếu có
                    if (isPlayingPlaylist) {
                        const playlistData = JSON.parse(localStorage.getItem('playlistData'));
                        if (playlistData) {
                            player.setPlaylistSongs(playlistData.playlistSongs);
                            player.setCurrentSongIndex(playlistData.currentSongIndex);
                        }
                    }

                    // Cập nhật thông tin footer player
                    document.getElementById('footerSongImg').setAttribute('src', footerSongImg);
                    document.getElementById('footerSongTitle').innerText = footerSongTitle;
                    document.getElementById('footerSongArtist').innerText = footerSongArtist;
                    document.getElementById('footer-lyrics-text').innerText = footerLyricsText;
                    document.getElementById('footer-lyrics-img').setAttribute('src', footerLyricsImg);
                    document.getElementById('footer').setAttribute('data-song-id', dataSongId);

                    player.attachSource(audioPath);
                    document.getElementById('footer').style.display = 'flex';

                    // Kiểm tra trạng thái yêu thích bài hát
                    checkLikedStatus(dataSongId);

                    footerAudioElement.load();

                    // Đặt lại thời gian và phát nếu cần
                    footerAudioElement.addEventListener('canplaythrough', function () {
                        footerAudioElement.currentTime = currentTime;

                        if (isPlaying) {
                            footerAudioElement.play();
                            const playIcon = document.querySelectorAll('.fa-play');
                            if (playIcon) {
                                playIcon.forEach(icon => {
                                    icon.classList.replace('fa-play', 'fa-pause');
                                });
                            }
                        } else {
                            footerAudioElement.pause();
                            const pauseIcon = document.querySelectorAll('.fa-pause');
                            if (pauseIcon) {
                                pauseIcon.forEach(icon => {
                                    icon.classList.replace('fa-pause', 'fa-play');
                                });
                            }
                        }
                    }, { once: true });
                }
            }
        } catch (error) {
            console.error('Error loading player state:', error);
        }
    });

    // Đóng overlay khi nhấn ra ngoài
    window.onclick = function (event) {
        if (event.target === document.getElementById("loginOverlay") || event.target === document.getElementById(
            "registerOverlay")) {
            closeOverlay();
        }
    };
    //Lyrics popup

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
// Open popup
export function openPopup() {
    document.getElementById('overlay').classList.add('active');
}

// Close popup
export function closePopup() {
    document.getElementById('overlay').classList.remove('active');
}
// Hiển thị form đăng nhập
export function showLoginForm() {
    document.getElementById("registerOverlay").style.display = "none"; // Ẩn form đăng ký
    document.getElementById("loginOverlay").style.display = "flex"; // Hiển thị form đăng nhập
}

// Hiển thị form đăng ký
export function showRegisterForm() {
    document.getElementById("loginOverlay").style.display = "none"; // Ẩn form đăng nhập
    document.getElementById("registerOverlay").style.display = "flex"; // Hiển thị form đăng ký
}

// Đóng các overlay
export function closeOverlay() {
    document.getElementById("loginOverlay").style.display = "none";
    document.getElementById("registerOverlay").style.display = "none";
}

export function likeSong() { //Thêm bài hát vào likeplaylsit nếu người dùng đã đăng nhập
    try {
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
    } catch (error) {
        console.error('Error liking song:', error);
    }
};

export function checkLikedStatus(songId) {
    try {
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
    } catch (error) {
        console.error('Error checking liked status:', error);
    }
};

// Di chuyển các hàm ra ngoài để có thể truy cập global
export function playAllSongs() {
    try {
        const songs = window.playlistSongs;
        if (typeof songs !== 'undefined' && songs.length > 0) {
            isPlayingPlaylist = true;
            currentSongIndex = 0;
            playCurrentSong();
        } else {
            console.error('No songs available in playlist');
        }
    } catch (error) {
        console.error('Error playing all songs:', error);
    }
};

export function playCurrentSong() {
    try {
        if (!playlistSongs || playlistSongs.length === 0) return;
        console.log('playCurrentSong');
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
    } catch (error) {
        console.error('Error playing current song:', error);
    }
};

// Đảm bảo các function không bị tree-shaken
if (typeof window !== 'undefined') {
    window.playAllSongs = playAllSongs;
    window.playCurrentSong = playCurrentSong;
    window.likeSong = likeSong;
    window.checkLikedStatus = checkLikedStatus;
    window.showLoginForm = showLoginForm;
    window.showRegisterForm = showRegisterForm;
    window.closeOverlay = closeOverlay;
    window.openPopup = openPopup;
    window.closePopup = closePopup;
    window.playPreviousSong = playPreviousSong;
    window.playNextSong = playNextSong;
    window.playPause = playPause;
    window.seekToTime = seekToTime;
    window.repeatSong = repeatSong;
    window.shuffleSong = shuffleSong;
    window.playRecommendedSong = playRecommendedSong;
    window.updateFooterPlayer = updateFooterPlayer;
    window.handleSongClick = handleSongClick;
    window.playAudioWithAd = playAudioWithAd;
    window.formatTime = formatTime;
    window.playAudio = playAudio;
    window.showAdPopup = showAdPopup;
    window.updateVolumeIcon = updateVolumeIcon;
}

export function playPreviousSong() {
    try {
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
    } catch (error) {
        console.error('Error playing previous song:', error);
    }
};


export function playNextSong() {
        if (isPlayingPlaylist && currentSongIndex < playlistSongs.length - 1) {
            currentSongIndex++;
            playCurrentSong();
            return;
        } else if (recommendedSongs.length > 0) {
            // Phát bài tiếp theo từ danh sách gợi ý
            const nextRecommendedSong = recommendedSongs.shift(); // Lấy bài đầu tiên và xóa khỏi danh sách
            playRecommendedSong(nextRecommendedSong);
            return;
        } else if (songs.length > 0) {
            // Phát bài tiếp theo từ danh sách bài hát
            const nextSong = songs.shift(); // Lấy bài đầu tiên và xóa khỏi danh sách
            playRecommendedSong(nextSong);
            return;
    }
};

export function playPause() {
    try {
        if (footerAudioElement.paused) {
            footerAudioElement.play();
            window.playIcon = document.querySelectorAll('.fa-play');
            if (playIcon) {
                playIcon.forEach(icon => {
                    icon.classList.replace('fa-play', 'fa-pause');
                });
            }
        } else {
            footerAudioElement.pause();
            window.pauseIcon = document.querySelectorAll('.fa-pause');
            if (pauseIcon) {
                pauseIcon.forEach(icon => {
                    icon.classList.replace('fa-pause', 'fa-play');
                });
            }
        }
    } catch (error) {
        console.error('Error playing/pausing audio:', error);
    }
};

export function seekToTime() {
    const newTime = (progressBar.value / 100) * footerAudioElement.duration;
    footerAudioElement.currentTime = newTime;
};

export function repeatSong() {
    const repeatIcon = document.querySelector('.fas.fa-repeat');
    if (repeatIcon.style.color === 'green') {
        repeatIcon.style.color = '#FFF';
    } else {
        repeatIcon.style.color = 'green';
    }
    window.isRepeat = !window.isRepeat;
};

export function shuffleSong() {
    const shuffleIcon = document.querySelector('.fas.fa-random');
    if (shuffleIcon.style.color === 'green') {
        shuffleIcon.style.color = '#FFF';
    } else {
        shuffleIcon.style.color = 'green';
    }
    window.isRandom = !window.isRandom;
};
export function playRecommendedSong(recommendedSong) {
    try {
        updateFooterPlayer(recommendedSong);
        // Thêm bài hát gợi ý vào lịch sử
        historySongs.push(recommendedSong);

        playAudioWithAd('storage/' + recommendedSong.audio_path);
    } catch (error) {
        console.error('Error playing recommended song:', error);
    }
};

export function updateFooterPlayer(song) {
    document.getElementById('footerSongImg').setAttribute('src', song.img_id ? `/image/${song.img_id}` : '');
    document.getElementById('footerSongTitle').innerText = song.song_name;
    document.getElementById('footerSongArtist').innerText = song.author.author_name;
    document.getElementById('footer-lyrics-text').innerText = song.lyric || '';
    document.getElementById('footer-lyrics-img').setAttribute('src', song.img_id ? `/image/${song.img_id}` : '');
    document.getElementById('footer').setAttribute('data-song-id', song.id);
    document.getElementById('footerAudioPlayer').setAttribute('src', 'storage/' + song.audio_path);
};

// Hàm cập nhật biểu tượng âm lượng
export function updateVolumeIcon(volume) {
    if (volume === 0) {
        volumeIcon.className = 'fas fa-volume-mute text-xl cursor-pointer';
    } else if (volume > 0 && volume <= 0.5) {
        volumeIcon.className = 'fas fa-volume-down text-xl cursor-pointer';
    } else {
        volumeIcon.className = 'fas fa-volume-up text-xl cursor-pointer';
    }
};

// Lắng nghe sự kiện click vào các bài hát
export function handleSongClick(songItem) {
    const songId = songItem.getAttribute('data-song-id');
    const songName = songItem.querySelector('#song-name').innerText;
    const songArtist = songItem.querySelector('#song-artist').innerText;
    const songImage = songItem.querySelector('img').getAttribute('src');
    const audioElement = songItem.querySelector('audio');
    const audioSrc = audioElement ? audioElement.getAttribute('src') : null;
    const lyricsText = songItem.querySelector('#lyrics-text').innerText;
    // Cập nhật thông tin vào footer
    document.getElementById('footerSongImg').setAttribute('src', songImage);
    document.getElementById('footerSongTitle').innerText = songName;
    document.getElementById('footerSongArtist').innerText = songArtist;
    document.getElementById('footer-lyrics-text').innerText = lyricsText;
    document.getElementById('footer-lyrics-img').setAttribute('src', songImage);
    document.getElementById('footer').setAttribute('data-song-id', songId);
    // Cập nhật nguồn âm thanh vào footer và kiểm tra nếu không có âm thanh
    if (audioSrc) {
        footerAudioElement.setAttribute('src', audioSrc);
        document.getElementById('footer').style.display = 'flex'; // Hiển thị footer khi có âm thanh
        footerAudioElement.currentTime = 0;
        progressBar.value = 0;
        const playButton = document.querySelector('.fa-play');
        if (playButton) {
            playButton.classList.replace('fa-play', 'fa-pause');
        }
        checkLikedStatus(songId);
        playAudioWithAd(audioSrc);
    } else {
        footerAudioElement.removeAttribute('src');
        document.getElementById('footer').style.display = 'none'; // Ẩn footer nếu không có âm thanh
    }
};

export function formatTime(timeInSeconds) {
    const minutes = Math.floor(timeInSeconds / 60);
    const seconds = Math.floor(timeInSeconds % 60);
    return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
}

export function playAudio(filePath) {
    if (!filePath) {
        console.error('Không có file để phát');
        return;
    }
    const songId = document.getElementById('footer').dataset.songId;
    checkLikedStatus(songId);
    player.attachSource(filePath); // Đính kèm nguồn DASH (MPD   file)
    footerAudioElement.load();
    footerAudioElement.play();
    const playIcon = document.querySelector('.fa-play');
    if (playIcon) {
        playIcon.classList.replace('fa-play', 'fa-pause');
    }
};

export function playAudioWithAd(filePath) {
    if ((user.isLoggedIn && user.plan === 'free') || !user.isLoggedIn) {
        // Hiển thị popup quảng cáo trước khi phát
        showAdPopup(() => {
            playAudio(filePath); // Gọi hàm playAudio sau khi tắt popup
        });
    } else {
        playAudio(filePath); // Người dùng có gói Premium
    }
};

export function showAdPopup(callback) {
    const adPopup = document.getElementById('adPopup');
    adPopup.style.display = 'flex';

    // Đóng popup và thực hiện callback
    window.closeAdPopup = function () {
        adPopup.style.display = 'none';
        if (typeof callback === 'function') callback();
    };
};