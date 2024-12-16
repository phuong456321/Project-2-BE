@push('css')
    <style>
        .footer img {
            position: absolute;
            margin-left: 2rem;
        }

        .footer .current-song {
            margin-right: 0;
        }

        .lyrics-text p {
            color: #999;
            /* Màu chữ mặc định */
            font-size: 16px;
            margin: 5px 0;
        }

        .lyrics-text p.active {
            color: #ff5722;
            /* Màu chữ nổi bật cho lyric hiện tại */
            font-weight: bold;
            font-size: 18px;
        }
    </style>
@endpush
<div id="footer"
    class="hidden fixed bottom-0 left-0 w-full bg-gray-900 text-white shadow-md flex items-center px-4 py-2 z-10 space-x-4 sm:space-x-2 lg:space-x-6"
    data-song-id="">

    <!-- Music Image -->
    <img id="footerSongImg" src="" alt="Music Image" class="w-12 h-12 sm:w-10 sm:h-10 md:w-16 md:h-16 rounded-md object-cover">

    <!-- Song Information -->
    <div class="flex flex-col flex-1 justify-center max-w-[10rem] sm:max-w-[15rem] md:max-w-[20rem] lg:max-w-[20rem]">
        <p id="footerSongTitle" class="text-sm font-semibold truncate">Tên bài hát</p>
        <p id="footerSongArtist" class="text-xs text-gray-400 truncate">Tên ca sĩ</p>
    </div>

    <!-- Controls -->
    <div class="flex items-center space-x-2 sm:space-x-1 md:space-x-4">
        <i class="fas fa-step-backward text-lg md:text-xl cursor-pointer" onclick="playPreviousSong()"></i>
        <i class="fas fa-play text-lg md:text-xl cursor-pointer" onclick="playPause()"></i>
        <i class="fas fa-step-forward text-lg md:text-xl cursor-pointer" onclick="playNextSong()"></i>
    </div>

    <!-- Progress -->
    <div class="hidden sm:flex items-center space-x-2 w-full max-w-[15rem] md:max-w-[30rem]">
        <input id="progressBar" type="range" max="100" min="0" value="0"
            class="w-full h-1 bg-gray-700 rounded-lg accent-blue-500" onclick="seekToTime()" />
        <span id="currentTime" class="text-xs">0:00</span>
        <span>/</span>
        <span id="totalTime" class="text-xs">0:00</span>
    </div>

    <!-- Actions -->
    <div class="flex items-center space-x-2 sm:space-x-1 md:space-x-4">
        <i class="fas fa-heart text-lg md:text-xl cursor-pointer" onclick="likeSong()"></i>
        <i class="fas fa-repeat text-lg md:text-xl cursor-pointer" onclick="repeatSong()"></i>
        <i class="fas fa-random text-lg md:text-xl cursor-pointer" onclick="shuffleSong()"></i>
        <!-- Thêm thanh trượt vào phần Actions -->
        <div class="volume-control flex items-center space-x-2 hidden sm:flex">
            <i id="volumeIcon" class="fas fa-volume-up text-lg md:text-xl cursor-pointer"></i>
            <input id="volumeSlider" type="range" min="0" max="1" step="0.1" value="1"
                class="w-16 sm:w-20 md:w-24 h-1 bg-gray-700 rounded-lg accent-blue-500" />
        </div>
        <i class="fa-solid fa-music text-lg md:text-xl cursor-pointer" id="toggleLyricsIcon"></i>
        <i class="fas fa-ellipsis-h text-lg md:text-xl cursor-pointer" onclick="openPopup()"></i>
        {{-- <i class="fas fa-list-ul text-lg md:text-xl cursor-pointer" onclick="openSongListPopup()"></i> --}}
    </div>

    <audio id="footerAudioPlayer" src="" controls hidden></audio>
</div>



<!-- Popup lyrics -->
<div id="lyricPopup" 
    class="popup-lyrics fixed right-0 top-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden w-full transition-transform transform translate-y-full lg:max-w-[83%] max-h-[89%] overflow-y-auto scrollbar-none overflow-x-hidden">
    <div class="bg-gray-800 text-white rounded-lg shadow-lg overflow-hidden w-full h-[80%] mt-16">
        <div class="lyrics-container flex flex-col md:flex-row h-full">
            <!-- Album Cover -->
            <div class="flex-shrink-0 flex items-center justify-center bg-gray-700 p-4">
                <img alt="Album cover" id="footer-lyrics-img" 
                    src="" 
                    class="w-48 h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 object-cover rounded-md" />
            </div>
            <!-- Lyrics -->
            <div class="flex-1 p-4 overflow-y-auto scrollbar-none">
                <!-- Tabs -->
                <div class="tabs flex space-x-4 border-b border-gray-600 mb-4">
                    <div class="tab active font-semibold text-base md:text-lg pb-2 border-b-2 border-blue-500">LYRIC</div>
                </div>
                <!-- Lyrics Text -->
                <div class="lyrics font-monospace text-sm md:text-base lg:text-xl leading-relaxed overflow-y-auto">
                    <p id="footer-lyrics-text" class="lyrics-text whitespace-pre-line"></p>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="adPopup" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
    <div class="!bg-white !p-6 !rounded-lg !w-4/5 !max-w-md !text-center">
        <!-- Quảng cáo AdSense -->
        <div class="popup-content">
            <span id="closePopup" class="close-btn">&times;</span>
            <h1 class="text-2xl font-bold mb-4">Quảng cáo</h1>
            <!-- Chèn mã quảng cáo Google AdSense -->
            {{-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4089886839959004"
                crossorigin="anonymous"></script>
            <!-- Nulltifly-home -->
            <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4089886839959004"
                data-ad-slot="5674451393" data-ad-format="auto" data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script> --}}
        </div>

        <button onclick="closeAdPopup()"
            class="px-6 py-2 bg-green-500 text-white font-medium rounded-md hover:bg-green-600 transition">
            Tiếp tục
        </button>
    </div>
</div>
@push('scripts')
    <script>
        const lyricsContainer = document.querySelector('.lyrics-text');
        // Cập nhật lyric hiện tại
        let lastActiveIndex = -1; // Biến lưu trạng thái lyric cuối cùng đã active

        function displayLyrics(currentTime) {
            const currentTimeSeconds = currentTime;
            const lyricElements = lyricsContainer.querySelectorAll('p');
            lyricElements.forEach(element => element.classList.remove('text-red-500'));

            let activeIndex = -1;

            for (let i = 0; i < lyrics.length; i++) {
                const lyric = lyrics[i];
                const lyricTime = convertTimeToSeconds(lyric.time);

                if (currentTimeSeconds >= lyricTime) {
                    lyricElements[i].classList.add('text-red-500');
                    activeIndex = i;
                } else {
                    break;
                }
            }

            // Chỉ cuộn nếu lyric mới khác với lyric cũ
            if (activeIndex !== -1 && activeIndex !== lastActiveIndex) {
                lastActiveIndex = activeIndex; // Cập nhật lyric cuối cùng đã active
                lyricElements[activeIndex].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                });
            }
        }
        // Hàm chuyển đổi thời gian từ "mm:ss.xx" thành giây
        function convertTimeToSeconds(timeStr) {
            const parts = timeStr.split(':');
            const minutes = parseInt(parts[0]);
            const seconds = parseFloat(parts[1]);
            return (minutes * 60) + seconds;
        }
        // Hiển thị toàn bộ lời bài hát
        function renderLyrics() {
            lyricsContainer.innerHTML = lyrics.map(lyric => `<p>${lyric.text.replace(/\n/g, '<br>')}</p>`).join('');
        }
    </script>
@endpush
