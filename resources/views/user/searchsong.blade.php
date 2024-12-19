@extends('user.layout')

@push('styles')
@vite('resources/css/searchsong.css')
@endpush


@section('content')

<body class="bg-black text-white">
    <div class="container mx-auto p-4">
        <!-- Navigation Tabs -->
        <div class="flex space-x-4 mb-4 ">
            <a class="text-white font-bold cursor-pointer no-underline hover:text-white hover:transition duration-100" href="#">
                NULLTIFLY MUSIC
            </a>
            <a class="text-gray-400 no-underline hover:text-white hover:transition duration-100" href="/library">
                LIBRARY
            </a>
        </div>
        <!-- Filter Buttons -->
        <div class="flex space-x-2 mb-4">
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full hover:text-blue-300 hover:transition duration-100">
                Song
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full hover:text-blue-300 hover:transition duration-100">
                Artist
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full hover:text-blue-300 hover:transition duration-100">
                Profile
            </button>
        </div>
        <!-- Top Result -->
        <div class="mb-4">
            <h2 class="text-xl font-bold mb-2">
            Top results
            </h2>
            @if (!$songs->isEmpty())
            @php $topSong = $songs->first(); @endphp
            <div class="bg-gray-800 p-4 rounded-lg flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 top-song-item"
                data-song-id="{{ $topSong->id }}" style="cursor: pointer;">
                <img alt="Album cover image" class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg"
                    src="{{ url('image/' . $topSong->img_id) }}" />
                <div class="flex-1">
                    <h3 class="text-base sm:text-lg font-bold text-white">
                        {{ $topSong->song_name }}
                    </h3>
                    <p class="text-gray-400 text-sm sm:text-base">
                        {{ $topSong->author->author_name }} • {{ $topSong->likes }} lượt thích •
                        {{ $topSong->play_count }} lượt phát
                    </p>
                    <audio src="{{ url('storage/' . $topSong->audio_path) }}" preload="auto"
                        style="display:none;" controls></audio>
                </div>

           
                <div class="flex flex-row sm:flex-col space-x-2 sm:space-x-0 sm:space-y-2 ml-auto">
                    <button class="bg-white text-black px-4 py-2 rounded-full text-sm sm:text-base">
                        Play 
                    </button>
                    <button class="bg-gray-700 text-white px-4 py-2 rounded-full text-sm sm:text-base">
                        Save 
                    </button>
                </div>
            </div>
            @else
            <p class="text-gray-400">There are no top results.</p>
            @endif
        </div>
        <!-- Songs List -->
        <div>
            <h2 class="text-xl font-bold mb-2">
                Song 
            </h2>
            <!-- Display a message if no songs are found -->
            @if ($songs->isEmpty())
            <p class="text-gray-400">No songs were found that matched your keywords.</p>
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