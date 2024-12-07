@extends('user.layout')

@push('styles')
    @vite('resources/css/searchsong.css')
@endpush


@section('content')

    <body class="bg-black text-white">
        <div class="container mx-auto p-4">
            <!-- Navigation Tabs -->
            <div class="flex space-x-4 mb-4">
                <a class="text-white font-bold" href="#">
                    NULLTIFLY MUSIC
                </a>
                <a class="text-gray-400" href="/library">
                    THƯ VIỆN
                </a>
            </div>
            <!-- Filter Buttons -->
            <div class="flex space-x-2 mb-4">
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Bài hát
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Video
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Đĩa nhạc
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Danh sách phát của cộng đồng
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Nghệ sĩ
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Podcast
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Tập podcast
                </button>
                <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                    Hồ sơ
                </button>
            </div>
            <!-- Top Result -->
            <div class="mb-4">
                <h2 class="text-xl font-bold mb-2">
                    Kết quả hàng đầu
                </h2>
                @if (!$songs->isEmpty())
                    @php $topSong = $songs->first(); @endphp
                    <div class="bg-gray-800 p-4 rounded-lg flex items-center top-song-item"
                        data-song-id="{{ $topSong->id }}" style="cursor: pointer;">
                        <img alt="Album cover image" class="w-20 h-20 rounded-lg mr-4" height="100"
                            src="{{ url('image/' . $topSong->img_id) }}" width="100" />
                        <div>
                            <h3 class="text-lg font-bold">{{ $topSong->song_name }}</h3>
                            <p class="text-gray-400">
                                {{ $topSong->author->author_name }} • {{ $topSong->likes }} lượt thích •
                                {{ $topSong->play_count }} lượt phát
                            </p>
                            <audio src="{{ url('storage/' . $topSong->audio_path) }}" preload="auto" style="display:none;"
                                controls></audio>
                        </div>
                        <div class="ml-auto flex space-x-2">
                            <button class="bg-white text-black px-4 py-2 rounded-full">
                                Phát
                            </button>
                            <button class="bg-gray-700 text-white px-4 py-2 rounded-full">
                                Lưu
                            </button>
                        </div>
                    </div>
                @else
                    <p class="text-gray-400">Không có kết quả nào hàng đầu.</p>
                @endif
            </div>
            <!-- Songs List -->
            <div>
                <h2 class="text-xl font-bold mb-2">
                    Bài hát
                </h2>
                <!-- Display a message if no songs are found -->
                @if ($songs->isEmpty())
                    <p class="text-gray-400">Không tìm thấy bài hát nào phù hợp với từ khóa của bạn.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($songs as $song)
                            <div class="flex items-center song-item" data-song-id="{{ $song->id }}"
                                style="cursor: pointer;">
                                <img alt="Song thumbnail" class="w-12 h-12 rounded-lg mr-4" height="50"
                                    src="{{ url('image/' . $song->img_id) }}" width="50" />
                                <div>
                                    <h3 class="text-lg">
                                        {{ $song->song_name }}
                                    </h3>
                                    <p class="text-gray-400">
                                        {{ $song->author->author_name }} • {{ $song->song_name }} • {{ $song->likes }}
                                        lượt
                                        thích • {{ $song->play_count }} lượt phát
                                    </p>
                                    <audio src="{{ url('storage/' . $song->audio_path) }}" preload="auto"
                                        style="display:none;" controls></audio>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endsection
    @extends('components.footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lắng nghe sự kiện click vào các bài hát
            function handleSongClick(songItem) {
                const songId = songItem.getAttribute('data-song-id');
                const songName = songItem.querySelector('h3').innerText;
                const songArtist = songItem.querySelector('.text-gray-400').innerText.split('•')[0].trim();
                const songImage = songItem.querySelector('img').getAttribute('src');
                const audioElement = songItem.querySelector('audio');
                const audioSrc = audioElement ? audioElement.getAttribute('src') : null;

                // Cập nhật thông tin vào footer
                document.getElementById('footerSongImg').setAttribute('src', songImage);
                document.getElementById('footerSongTitle').innerText = songName;
                document.getElementById('footerSongArtist').innerText = songArtist;
                document.getElementById('footer').setAttribute('data-song-id', songId);
                // Cập nhật nguồn âm thanh vào footer và kiểm tra nếu không có âm thanh
                if (audioSrc) {
                    footerAudioElement.setAttribute('src', audioSrc);
                    document.getElementById('footer').style.display = 'flex'; // Hiển thị footer khi có âm thanh
                    const playButton = document.querySelector('.fa-play');
                    if (playButton) {
                        playButton.classList.replace('fa-play', 'fa-pause');
                    }
                    playAudioWithAd(audioSrc);
                } else {
                    footerAudioElement.removeAttribute('src');
                    document.getElementById('footer').style.display = 'none'; // Ẩn footer nếu không có âm thanh
                }
            }

            // Áp dụng sự kiện click cho tất cả các bài hát
            document.querySelectorAll('.song-item').forEach(item => {
                item.addEventListener('click', function() {
                    handleSongClick(this);
                });
            });

            // Áp dụng sự kiện click cho bài hát trong mục "Kết quả hàng đầu"
            const topSongItem = document.querySelector('.top-song-item');
            if (topSongItem) {
                topSongItem.addEventListener('click', function() {
                    handleSongClick(this);
                });
            }
        });
    </script>
