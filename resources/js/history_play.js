window.addEventListener('beforeunload', function () {
    const footerSongImg = document.getElementById('footerSongImg').getAttribute('src');
    const footerSongTitle = document.getElementById('footerSongTitle').innerText;
    const footerSongArtist = document.getElementById('footerSongArtist').innerText;
    const footerLyricsText = document.getElementById('footer-lyrics-text').innerText;
    const footerLyricsImg = document.getElementById('footer-lyrics-img').getAttribute('src');
    const dataSongId = document.getElementById('footer').getAttribute('data-song-id');
    const currentTime = footerAudioElement.currentTime;
    const isPlaying = !footerAudioElement.paused;
    const audioPath = player.getSource();
    localStorage.setItem('playerState', JSON.stringify({
        dataSongId,
        footerSongImg,
        footerSongTitle,
        footerSongArtist,
        footerLyricsText,
        footerLyricsImg,
        currentTime,
        isPlaying,
        audioPath
    }));
});

window.addEventListener('load', function () {
    const playerState = JSON.parse(localStorage.getItem('playerState'));
    if (playerState) {
        const { dataSongId, footerSongImg, footerSongTitle, footerSongArtist, footerLyricsText, footerLyricsImg, currentTime, isPlaying, audioPath } = playerState;

        // Khôi phục bài hát
        if (dataSongId) {
            document.getElementById('footerSongImg').setAttribute('src', footerSongImg);
            document.getElementById('footerSongTitle').innerText = footerSongTitle;
            document.getElementById('footerSongArtist').innerText = footerSongArtist;
            document.getElementById('footer-lyrics-text').innerText = footerLyricsText;
            document.getElementById('footer-lyrics-img').setAttribute('src', footerLyricsImg);
            document.getElementById('footer').setAttribute('data-song-id', dataSongId);
            player.attachSource(audioPath);
            document.getElementById('footer').style.display = 'flex';
            // Phát tiếp nếu trước đó đang phát
            if (isPlaying) {
                footerAudioElement.play();
                document.querySelector('.fa-play').classList.replace('fa-play', 'fa-pause');
            }
            // Đặt lại thời gian phát nếu có
            footerAudioElement.addEventListener('loadedmetadata', function () {
                footerAudioElement.currentTime = currentTime;
            });            
        }
    }
});
