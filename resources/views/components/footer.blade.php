@push('css')
    <style>
        .footer img {
            position: absolute;
            margin-left: 2rem;
        }

        .footer .current-song {
            margin-right: 0;
        }

        .tab.active {
            background-color: #1f2937;
            /* Màu nền cho tab active */
            color: white;
            /* Màu chữ cho tab active */
        }
    </style>
@endpush
<div id="footer"
    class="hidden fixed bottom-0 left-0 w-full bg-gray-900 text-white shadow-md flex items-center px-4 py-2 z-30 space-x-4 sm:space-x-2 lg:space-x-6"
    data-song-id="">

    <!-- Music Image -->
    <img id="footerSongImg" src="" alt="Music Image"
        class="w-12 h-12 sm:w-10 sm:h-10 md:w-16 md:h-16 rounded-md object-cover">

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
    </div>

    <audio id="footerAudioPlayer" src="" controls hidden></audio>
</div>



<!-- Popup lyrics -->
<div id="lyricPopup"
    class="popup-lyrics fixed left-0 top-0 bg-black bg-opacity-75 flex items-center justify-center z-20 hidden w-full lg:max-w-[calc(100%-250px)] max-h-[94%] lg:max-h-[90%] overflow-hidden h-[100dvh] lg:ml-[250px] transition-transform transform translate-y-full">
    <div class="bg-gray-800 text-white rounded-lg shadow-lg w-full h-[100%] flex flex-col lg:flex-row">
        <!-- Album Cover -->
        <div class="flex-shrink-0 flex items-center justify-center bg-gray-700 p-4">
            <img alt="Album cover" id="footer-lyrics-img" src=""
                class="w-48 h-48 md:w-56 md:h-56 lg:w-64 lg:h-64 object-cover rounded-md" />
        </div>

        <!-- Lyrics Section -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Tabs -->
            <div class="tabs flex space-x-4 border-b border-gray-600 p-4">
                <div class="tab active font-semibold text-base md:text-lg pb-2" onclick="showTab('lyrics')">LYRIC</div>
                <div class="tab font-semibold text-base md:text-lg pb-2" onclick="showTab('playlist')">QUEUE</div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 tab-content scrollbar-none" id="lyrics">
                <p id="footer-lyrics-text"
                    class="lyrics-text font-monospace text-sm md:text-base lg:text-xl leading-relaxed whitespace-pre-line">
                    <!-- Lyrics content here -->
                </p>
            </div>

            <!-- Playlists -->
            <div class="playlist tab-content hidden p-4 overflow-y-auto scrollbar-none" id="playlist">
                <!-- Hiển thị bài hát hiện tại -->
            </div>
        </div>
    </div>
</div>



<div id="adPopup" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
    <div class="!bg-white !p-6 !rounded-lg !w-4/5 !max-w-md !text-center">
        <!-- Quảng cáo AdSense -->
        <div class="popup-content text-black">
             <h2>Quảng cáo!</h2>
            <div id="adsense-ad">
                <!-- Mã quảng cáo Google AdSense -->
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4089886839959004"
     crossorigin="anonymous"></script>
            </div>
        </div>

        <button onclick="closeAdPopup()"
            class="px-6 py-2 bg-green-500 text-white font-medium rounded-md hover:bg-green-600 transition">
            Tiếp tục
        </button>
    </div>
</div>
@push('scripts')
    <script>
        function displayQueue() {
            $.ajax({
                url: '/get-queue',
                type: 'GET',
                success: function(data) {
                    if(queueSongs === undefined || queueSongs === null || queueSongs == []) {
                        queueSongs = [];
                    }
                    if(queueSongs.length < 1) {                        
                        queueSongs.push(data);
                        queueSongs = queueSongs.flat();
                    }
                    const playlist = document.getElementById('playlist');
                    // Kiểm tra xem currentSong có tồn tại từ window không
                    if (window.currentSong) {
                        // Nếu có currentSong, hiển thị thông tin bài hát hiện tại
                        playlist.innerHTML = `
                    <h2 class="text-xl font-bold">Now playing</h2>
                    <div class="flex items-center gap-4 mb-4 current-song">
                        <img alt="Album cover" class="w-16 h-16 sm:w-20 sm:h-20 rounded" src="{{ url('image/') }}/${window.currentSong.img_id}" />
                        <div>
                            <h3 class="font-bold text-base sm:text-lg lg:text-xl">
                                ${window.currentSong.song_name}
                            </h3>
                            <p class="text-sm sm:text-base">
                                ${window.currentSong.author.author_name} • ${window.currentSong.play_count} Plays
                            </p>
                        </div>
                    </div>
                `;
                    }

                    // Nếu có dữ liệu trả về từ AJAX (song), tiếp tục xử lý
                    if (queueSongs && queueSongs.length > 0) {
                        // Hiển thị tiêu đề "Next up"
                        playlist.innerHTML += `<h2 class="text-xl font-bold">Next up</h2>`;
                        // Lặp qua các bài hát trả về và thêm vào playlist
                        queueSongs.forEach(function(song) {
                            playlist.innerHTML += `
                        <div class="flex items-center gap-4 mb-2 cursor-pointer" data-song-id="${song.id}">
                            <img alt="Album cover" class="w-12 h-12 sm:w-16 sm:h-16 rounded" src="{{ url('image/') }}/${song.img_id}" />
                            <div>
                                <h3 class="font-bold text-sm sm:text-base lg:text-lg">${song.song_name}</h3>
                                <p class="text-xs sm:text-sm">${song.author.author_name} • ${song.play_count}</p>
                            </div>
                        </div>
                    `;
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", status, error);
                }
            });
        }

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
            lyricsContainer.innerHTML = lyrics.map(lyric => `<p>${lyric.text.replace(/\n/g, '<br>')}</p>`)
                .join('');
        }

        function showTab(tabName) {
            // Ẩn tất cả các tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Loại bỏ trạng thái active khỏi tất cả các tab
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Hiển thị nội dung tab được chọn
            document.getElementById(tabName).classList.remove('hidden');

            // Thêm class active cho tab hiện tại
            const activeTab = document.querySelector(`[onclick="showTab('${tabName}')"]`);
            activeTab.classList.add('active');
        }
    </script>
@endpush
