@extends('user.layout')

@section('title', 'Playlist')

@push('styles')
    @vite('resources/css/playist.css')
@endpush
<?php
$filledSongs = collect($songs)->take(4); //Lấy tối đa bài hát
$remainingSlots = 4 - $filledSongs->count(); // Tính số ảnh trống cần thêm
?>
@section('content')
    <div class="container">
        <div class="left-panel space-y-4">
            <div class="album-cover grid grid-cols-2 gap-4">
                @foreach ($filledSongs as $song)
                    <div class="h-[9.5rem] w-[9.5rem] bg-zinc-400 rounded-lg overflow-hidden shadow-lg">
                        <img alt="Album cover" class="h-full w-full object-cover" src="{{ url('image/' . $song->img_id) }}" />
                    </div>
                @endforeach
                @for ($i = 0; $i < $remainingSlots; $i++)
                    <div class="h-[9.5rem] w-[9.5rem] bg-gray-500 flex items-center justify-center rounded-lg shadow-lg">
                        <svg class="h-8 w-8 text-cyan-400" role="img" aria-hidden="true" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M6 3h15v15.167a3.5 3.5 0 1 1-3.5-3.5H19V5H8v13.167a3.5 3.5 0 1 1-3.5-3.5H6V3zm0 13.667H4.5a1.5 1.5 0 1 0 1.5 1.5v-1.5zm13 0h-1.5a1.5 1.5 0 1 0 1.5 1.5v-1.5z">
                            </path>
                        </svg>
                    </div>
                @endfor
            </div>

            <div class="playlist-info mt-4">
                <h2 class="text-lg font-semibold text-white">
                    {{ \App\Models\Playlist::find($playlist_id)->name }}
                </h2>
                <div class="flex items-center space-x-2 text-gray-300 mt-2 justify-center">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span class="text-white font-medium">{{ Auth::user()->name }}</span>
                </div>
                <p class="text-sm text-gray-400 mt-2">
                    {{ count($songs) }} bản nhạc • {{ $totalDuration }}
                </p>
                <div class="actions flex space-x-3 mt-4">
                    <button class="p-2 bg-gray-700 hover:bg-gray-600 text-white rounded-full">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-full" onclick="playAllSongs()">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="p-2 bg-gray-700 hover:bg-gray-600 text-white rounded-full">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="song-list">
                <h3>
                    Sắp xếp
                </h3>
                @if (count($songs) == 0)
                    <p>
                        Không có bài hát nào trong danh sách phát này
                    </p>
                @else
                    @foreach ($songs as $song)
                        <div class="song-item" data-song-id="{{ $song->id }}">
                            <div class="song-info cursor-pointer">
                                <!-- Hiển thị ảnh bìa bài hát -->
                                <img alt="{{ $song->song_name }} cover" height="50"
                                    src="{{ url('image/' . $song->img_id) }}" width="50" />

                                <div>
                                    <!-- Hiển thị tên bài hát và nghệ sĩ -->
                                    <p id="song-name">{{ $song->song_name }}</p>
                                    <p id="song-artist">{{ $song->author_name }}</p>
                                    <!-- Bạn có thể thay đổi cách lấy tên nghệ sĩ theo cách bạn lưu trữ trong cơ sở dữ liệu -->
                                </div>
                                <audio src="{{ url('storage/' . $song->audio_path) }}" preload="auto" style="display:none;"
                                    controls></audio>
                                <p id="lyrics-text" class="whitespace-pre-line hidden"> {{ $song->lyric }} </p>
                            </div>
                            <div class="song-actions">
                                <!-- Hiển thị thời gian bài hát -->
                                <p>{{ $song->duration }}</p>
                                <!-- Cần thay thế $song->duration bằng thời gian thực tế nếu có -->
                                <i class="fas fa-thumbs-up">
                                </i>
                            </div>

                        </div>
                    @endforeach
                @endif


            </div>

            {{-- Đề xuất --}}
            {{-- <div class="song-list">
                <h3>
                    Đề xuất
                </h3>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 4" height="50"
                            src="https://storage.googleapis.com/a1aa/image/CDfNMldiBdVuKCSrDHPAxWoTbTxsBazcoRBIsBQmSxkbcy5JA.jpg"
                            width="50" />
                        <div>
                            <p>
                                MASHA ULTRAFUNK
                            </p>
                            <p>
                                HISTED và TXVSTERPLAYA • MASHA ULTRAFUNK
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            1:34
                        </p>
                        <i class="fas fa-plus">
                        </i>
                    </div>
                </div>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 5" height="50"
                            src="https://storage.googleapis.com/a1aa/image/rBBbwmoOFh4kF1wQiImo3c1bPwHXoVIibQXz3NBeUnQccy5JA.jpg"
                            width="50" />
                        <div>
                            <p>
                                P.I.M.P.
                            </p>
                            <p>
                                50 Cent • Get Rich Or Die Tryin'
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            4:10
                        </p>
                        <i class="fas fa-plus">
                        </i>
                    </div>
                </div>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 6" height="50"
                            src="https://storage.googleapis.com/a1aa/image/Kf8eSjiYevfa2STMROexpnmpmx4KayGajwW4GRiOkBQgGnceE.jpg"
                            width="50" />
                        <div>
                            <p>
                                Điều anh biết (Lofi)
                            </p>
                            <p>
                                Chi Dân
                            </p>
                        </div>
                    </div>
                    <div class="song-actions">
                        <p>
                            5:15
                        </p>
                        <i class="fas fa-plus">
                        </i>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
@push("scripts")
<script>
    // Chuẩn hóa dữ liệu bài hát trước khi gán
    window.playlistSongs = @json($songs)
</script>
@endpush