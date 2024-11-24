@extends('user.layout')

@push('styles')
    @vite('resources/css/searchsong.css')
@endpush

@push('styles')
    @vite('resources/css/searchsong.css')
@endpush


@section('content')

    <body class="bg-black text-white">
        <div class="container mx-auto p-4">
            <!-- Search Bar -->
            <div class="flex items-center  justify-center mb-4 flex justify-end">
                <input class="bg-gray-800 text-white rounded-full px-4 py-2 w-full" placeholder="Tìm kiếm bài hát, nghệ sĩ..."
                    value="{{ $query }}" type="text" />
                <div class="icon-search-song">
                    <i class="fas fa-search text-gray-400">
                    </i>
                </div>
            </div>
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
                <div class="bg-gray-800 p-4 rounded-lg flex items-center">
                    <img alt="Album cover image" class="w-20 h-20 rounded-lg mr-4" height="100"
                        src="https://storage.googleapis.com/a1aa/image/GPF0MNJK7k6wOVL61xubHPlDxNUIffFSaJeugmOGbFUaNNonA.jpg"
                        width="100" />
                    <div>
                        <h3 class="text-lg font-bold">
                            BÍCH THƯỢNG QUAN x VẠN SỰ TÙY DUYÊN - THANH HƯNG FT ĐỨC TỪ REMIX- HOT...
                        </h3>
                        <p class="text-gray-400">
                            Video • Đức Tư Remix • 134 N lượt xem • 6:11
                        </p>
                    </div>
                    <div class="ml-auto flex space-x-2">
                        <button class="bg-white text-black px-4 py-2 rounded-full">
                            Phát
                        </button>
                        <button class="bg-gray-700 text-white px-4 py-2 rounded-full">
                            Lưu
                        </button>
                        <!-- <i class="fas fa-ellipsis-h text-gray-400">
                                                </i> -->
                    </div>
                </div>
            </div>
            <!-- Songs List -->
            <div>
                <h2 class="text-xl font-bold mb-2">
                    Bài hát
                </h2>

                <div class="space-y-4">
                    @foreach ($songs as $song)
                        <div class="flex items-center song-item" data-song-id="{{ $song->song_id }}"
                            style="cursor: pointer;">
                            <img alt="Song thumbnail" class="w-12 h-12 rounded-lg mr-4" height="50"
                                src="{{ url('image/' . $song->img_id) }}" width="50" />
                            <div>
                                <h3 class="text-lg">
                                    {{ $song->song_name }}
                                </h3>
                                <p class="text-gray-400">
                                    {{ $song->author->author_name }} • {{ $song->song_name }} • {{ $song->likes }} lượt
                                    thích • {{ $song->play_count }} lượt phát
                                </p>
                                <audio src="{{ url('audio/' . $song->audio_path) }}" preload="auto" style="display:none;"
                                    controls></audio>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endsection
    @include('components.footer')

    <script>
        // Lắng nghe sự kiện click vào các bài hát
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.song-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Lấy dữ liệu bài hát từ thẻ dữ liệu
                    const songId = this.getAttribute('data-song-id');
                    const songName = this.querySelector('h3').innerText;
                    const songArtist = this.querySelector('.text-gray-400').innerText.split('•')[0]
                        .trim();
                    const songImage = this.querySelector('img').getAttribute('src');
                    const audioElement = this.querySelector('audio');
                    const audioSrc = audioElement ? audioElement.getAttribute('src') : null;

                    // Cập nhật thông tin vào footer
                    document.getElementById('footerSongImg').setAttribute('src', songImage);
                    document.getElementById('footerSongTitle').innerText = songName;
                    document.getElementById('footerSongArtist').innerText = songArtist;

                    // Cập nhật nguồn âm thanh vào footer và kiểm tra nếu không có âm thanh
                    const footerAudioElement = document.getElementById('footerAudioPlayer');
                    if (audioSrc) {
                        footerAudioElement.setAttribute('src', audioSrc);
                        document.getElementById('footer').style.display = 'flex'; // Hiển thị footer khi có âm thanh
                    } else {
                        footerAudioElement.removeAttribute('src');
                        document.getElementById('footer').style.display = 'none'; // Ẩn footer nếu không có âm thanh
                    }
                });
            });
        });
    </script>
