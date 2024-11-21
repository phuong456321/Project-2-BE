// Lấy các phần tử DOM
const playPauseBtn = document.querySelector('.play-pause');
const playPauseIcon = playPauseBtn.querySelector('i');
const prevBtn = document.querySelector('.fa-step-backward');
const nextBtn = document.querySelector('.fa-step-forward');
const repeatBtn = document.querySelector('.fa-sync-alt');
const shuffleBtn = document.querySelector('.fa-random');
const seekBar = document.querySelector('.seek-bar');
const currentTimeElem = document.querySelector('.current-time');
const totalTimeElem = document.querySelector('.total-time');
const volumeBar = document.querySelector('.volume-bar');

// Các biến trạng thái
let isPlaying = false;
let isRepeating = false;
let isShuffling = false;
let currentSongIndex = 0;
let audio = new Audio(); // Tạo đối tượng audio
const songs = [
  { url: 'song1.mp3', title: 'Bài hát 1', artist: 'Ca sĩ 1', duration: '3:41' },
  { url: 'song2.mp3', title: 'Bài hát 2', artist: 'Ca sĩ 2', duration: '4:12' },
  { url: 'song3.mp3', title: 'Bài hát 3', artist: 'Ca sĩ 3', duration: '2:58' },
];

// Cập nhật bài hát
function loadSong(index) {
  const song = songs[index];
  audio.src = song.url;
  currentTimeElem.textContent = '0:00';
  totalTimeElem.textContent = song.duration;
  document.querySelector('.song-name').textContent = song.title;
  document.querySelector('.artist-name').textContent = song.artist;
}

// Phát/tạm dừng nhạc
playPauseBtn.addEventListener('click', () => {
  if (isPlaying) {
    audio.pause();
  } else {
    audio.play();
  }
  isPlaying = !isPlaying;
  playPauseIcon.classList.toggle('fa-play', !isPlaying);
  playPauseIcon.classList.toggle('fa-pause', isPlaying);
});

// Chuyển bài trước
prevBtn.addEventListener('click', () => {
  if (isShuffling) {
    currentSongIndex = Math.floor(Math.random() * songs.length);
  } else {
    currentSongIndex = (currentSongIndex - 1 + songs.length) % songs.length;
  }
  loadSong(currentSongIndex);
  audio.play();
  isPlaying = true;
  playPauseIcon.classList.add('fa-pause');
  playPauseIcon.classList.remove('fa-play');
});

// Chuyển bài sau
nextBtn.addEventListener('click', () => {
  if (isShuffling) {
    currentSongIndex = Math.floor(Math.random() * songs.length);
  } else {
    currentSongIndex = (currentSongIndex + 1) % songs.length;
  }
  loadSong(currentSongIndex);
  audio.play();
  isPlaying = true;
  playPauseIcon.classList.add('fa-pause');
  playPauseIcon.classList.remove('fa-play');
});

// Lặp lại bài hát
repeatBtn.addEventListener('click', () => {
  isRepeating = !isRepeating;
  repeatBtn.classList.toggle('active', isRepeating);
  audio.loop = isRepeating;
});

// Phát ngẫu nhiên
shuffleBtn.addEventListener('click', () => {
  isShuffling = !isShuffling;
  shuffleBtn.classList.toggle('active', isShuffling);
});

// Thanh tiến trình
audio.addEventListener('timeupdate', () => {
  const currentTime = Math.floor(audio.currentTime);
  const duration = Math.floor(audio.duration);
  seekBar.value = (currentTime / duration) * 100;
  currentTimeElem.textContent = formatTime(currentTime);
});

// Điều chỉnh tiến trình nhạc
seekBar.addEventListener('input', (e) => {
  const seekTo = (audio.duration * e.target.value) / 100;
  audio.currentTime = seekTo;
});

// Điều chỉnh âm lượng
volumeBar.addEventListener('input', (e) => {
  audio.volume = e.target.value / 100;
});

// Định dạng thời gian
function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const secs = Math.floor(seconds % 60);
  return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
}

// Tự động chuyển bài khi hết bài hát
audio.addEventListener('ended', () => {
  if (isRepeating) {
    audio.play();
  } else if (isShuffling) {
    currentSongIndex = Math.floor(Math.random() * songs.length);
  } else {
    currentSongIndex = (currentSongIndex + 1) % songs.length;
  }
  loadSong(currentSongIndex);
  audio.play();
});
// Hàm ẩn hoặc hiện thanh nhạc
function toggleMusicPlayer() {
  var musicPlayer = document.getElementById('musicPlayer');
  var audioPlayer = document.getElementById('audioPlayer');

  // Nếu nhạc đang phát, hiển thị thanh nhạc
  if (!audioPlayer.paused) {
    musicPlayer.classList.add('active');
  } else {
    musicPlayer.classList.remove('active');
  }
}

// Xử lý khi nhạc bắt đầu phát
document.getElementById('audioPlayer').addEventListener('play', function () {
  document.getElementById('musicPlayer').classList.add('active');
});

// Xử lý khi nhạc dừng lại
document.getElementById('audioPlayer').addEventListener('pause', function () {
  document.getElementById('musicPlayer').classList.remove('active');
});



// Khởi tạo bài hát đầu tiên
loadSong(currentSongIndex);
