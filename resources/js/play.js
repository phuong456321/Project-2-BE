// Khai báo biến global
let currentSongIndex = 0;
let isPlayingPlaylist = false;
let playlistSongs = [];

document.addEventListener('DOMContentLoaded', function () {
    // Lắng nghe sự kiện click vào các bài hát
    function handleSongClick(songItem) {
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
    }

    // Áp dụng sự kiện click cho tất cả các bài hát
    document.querySelectorAll('.song-item').forEach(item => {
        item.addEventListener('click', function () {
            handleSongClick(this);
        });
    });

    // Áp dụng sự kiện click cho bài hát trong mục "Kết quả hàng đầu"
    const topSongItem = document.querySelector('.top-song-item');
    if (topSongItem) {
        topSongItem.addEventListener('click', function () {
            handleSongClick(this);
        });
    }

    // Thêm event listener cho khi bài hát kết thúc
    footerAudioElement.addEventListener('ended', function () {
        if (isPlayingPlaylist) {
            currentSongIndex++;
            if (currentSongIndex < playlistSongs.length) {
                playCurrentSong();
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
    });
});