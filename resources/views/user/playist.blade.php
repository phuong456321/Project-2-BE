@extends('user.layout')

@section('title', 'Playist')

@push('styles')
@vite('resources/css/playist.css')
@endpush



@section('content')

<body>
    <div class="container">
        <div class="left-panel">
            <div class="album-cover">
                <img alt="Album cover 1" height="150" src="https://storage.googleapis.com/a1aa/image/BEmMbvrRne3UcqIsWscmafVu1AyjrGCp9EnYeszIpKdhxJnnA.jpg" width="150" />
                <img alt="Album cover 2" height="150" src="https://storage.googleapis.com/a1aa/image/cvizA7M7ipZbNd2L7StmwNI91LDfWwWzrTsIyD0kUUCbcy5JA.jpg" width="150" />
                <img alt="Album cover 3" height="150" src="https://storage.googleapis.com/a1aa/image/obTGgLJmPwruFRjgfKxMcYdlC7RVM70nPZmJoqctAUSe4kzTA.jpg" width="150" />
                <img alt="Album cover 4" height="150" src="https://storage.googleapis.com/a1aa/image/UD5Qe7PW4zW6HCQRoDXH9zArXZFr9Crhzir4XqV2qWNdcy5JA.jpg" width="150" />
            </div>
            <div class="playlist-info">
                <h2>
                    pock
                </h2>
                <div class="user-info">
                    <i class="fas fa-user-circle">
                    </i>
                    Dat Le
                </div>
                <p>
                    Danh sách phát • Công khai • 2024
                </p>
                <p>
                    3 bản nhạc • 11 phút, 18 giây
                </p>
                <p>
                    pock
                </p>
                <div class="actions">
                    <button>
                        <i class="fas fa-edit">
                        </i>
                    </button>
                    <button>
                        <i class="fas fa-play">
                        </i>
                    </button>
                    <button>
                        <i class="fas fa-ellipsis-h">
                        </i>
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
                <div class="song-item">
                    <div class="song-info">
                        <!-- Hiển thị ảnh bìa bài hát -->
                        <img alt="{{ $song->song_name }} cover" height="50" src="https://storage.googleapis.com/a1aa/image/EfZvf2K5ZQlCdkOeZ51UWtSqHzJWjJAUXTvaZICHcfVsjTOPB.jpg" width="50" />

                        <div>
                            <!-- Hiển thị tên bài hát và nghệ sĩ -->
                            <p>{{ $song->song_name }}</p>
                            <p>{{ $song->author_name }}</p>  <!-- Bạn có thể thay đổi cách lấy tên nghệ sĩ theo cách bạn lưu trữ trong cơ sở dữ liệu -->
                        </div>
                    </div>
                    <div class="song-actions">
                        <!-- Hiển thị thời gian bài hát -->
                        <p>{{ $song->duration }}</p>  <!-- Cần thay thế $song->duration bằng thời gian thực tế nếu có -->
                        <i class="fas fa-thumbs-up">
                        </i>
                    </div>
                    
                </div>
            @endforeach
                @endif
                

            </div>

            {{-- Đề xuất --}}
            <div class="song-list">
                <h3>
                    Đề xuất
                </h3>
                <div class="song-item">
                    <div class="song-info">
                        <img alt="Song cover 4" height="50" src="https://storage.googleapis.com/a1aa/image/CDfNMldiBdVuKCSrDHPAxWoTbTxsBazcoRBIsBQmSxkbcy5JA.jpg" width="50" />
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
                        <img alt="Song cover 5" height="50" src="https://storage.googleapis.com/a1aa/image/rBBbwmoOFh4kF1wQiImo3c1bPwHXoVIibQXz3NBeUnQccy5JA.jpg" width="50" />
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
                        <img alt="Song cover 6" height="50" src="https://storage.googleapis.com/a1aa/image/Kf8eSjiYevfa2STMROexpnmpmx4KayGajwW4GRiOkBQgGnceE.jpg" width="50" />
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
            </div>
        </div>
    </div>
</body>

</html>


@endsection