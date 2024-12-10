@push('css')
    <style>
        .footer img {
            position: absolute;
            margin-left: 2rem;
        }

        .footer .current-song {
            margin-right: 0;
        }
    </style>
@endpush
<div id="footer"
    class="hidden fixed bottom-0 left-0 w-full bg-gray-900 text-white shadow-md flex items-center px-4 py-2 z-10 space-x-4"
    data-song-id="">

    <!-- Music Image -->
    <img id="footerSongImg" src="" alt="Music Image" class="w-16 h-16 rounded-md object-cover">

    <!-- Song Information -->
    <div class="flex flex-col flex-1 justify-center max-w-[20rem]">
        <p id="footerSongTitle" class="text-sm font-semibold truncate">Tên bài hát</p>
        <p id="footerSongArtist" class="text-xs text-gray-400 truncate">Tên ca sĩ</p>
    </div>

    <!-- Controls -->
    <div class="flex items-center space-x-4">
        <i class="fas fa-step-backward text-xl cursor-pointer" onclick="playPreviousSong()"></i>
        <i class="fas fa-play text-xl cursor-pointer" onclick="playPause()"></i>
        <i class="fas fa-step-forward text-xl cursor-pointer" onclick="playNextSong()"></i>
    </div>

    <!-- Progress -->
    <div class="flex items-center space-x-2 w-[30rem] max-w-full">
        <input id="progressBar" type="range" max="100" min="0" value="0"
            class="w-full h-1 bg-gray-700 rounded-lg accent-blue-500" onclick="seekToTime()"/>
        <span id="currentTime" class="text-xs">0:00</span>
        <span>/</span>
        <span id="totalTime" class="text-xs">0:00</span>
    </div>

    <!-- Actions -->
    <div class="flex items-center space-x-4">
        <i class="fas fa-heart text-xl cursor-pointer" onclick="likeSong()"></i>
        <i class="fas fa-repeat text-xl cursor-pointer" onclick="repeatSong()"></i>
        <i class="fas fa-random text-xl cursor-pointer" onclick="shuffleSong()"></i>
        <!-- Thêm thanh trượt vào phần Actions -->
        <div class="volume-control flex items-center space-x-2">
            <i id="volumeIcon" class="fas fa-volume-up text-xl cursor-pointer"></i>
            <input id="volumeSlider" type="range" min="0" max="1" step="0.1" value="1"
                class="w-24 h-1 bg-gray-700 rounded-lg accent-blue-500" />
        </div>
        <i class="fa-solid fa-music text-xl cursor-pointer" id="toggleLyricsIcon"></i>
        <i class="fas fa-ellipsis-h text-xl cursor-pointer" onclick="openPopup()"></i>
        <i class="fas fa-list-ul text-xl cursor-pointer" onclick="openSongListPopup()"></i>
    </div>

    <audio id="footerAudioPlayer" src="" controls hidden></audio>
</div>



<!-- Popup lyrics -->
<div id="lyricPopup" class="popup-lyrics hidden">
    <div class="popup-lyrics-content">
        <div class="lyrics-container">
            <div class="left">
                <img alt="Album cover" id="footer-lyrics-img" height="600" src="" width="600" />
            </div>
            <div class="right">
                <div class="tabs">
                    <div class="tab active">LYRIC</div>
                </div>
                <div class="lyrics">
                    <p id="footer-lyrics-text" style="white-space: pre-line;"></p>
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
