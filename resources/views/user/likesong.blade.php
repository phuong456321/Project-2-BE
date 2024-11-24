@extends('user.layout')

@section('title', 'likesong')

@push('styles')
@vite('resources/css/likesong.css')
@endpush




@section('content')

<div class="likesong-container">
    <div class="likesong-sidebar">
        <img alt="Album cover with a thumbs up icon" height="200" src="https://storage.googleapis.com/a1aa/image/BQzvfqyszhze70CvcHcRjyBtiiHfaeT5f65gbjJB4YNEle38E.jpg" width="200" />
        <h1>
            Nhạc đã thích
        </h1>
        <div class="likesong-user-info">
            <div class="likesong-avatar">
                D
            </div>
            <div class="likesong-username">
                Dat Le
            </div>
        </div>
        <div class="likesong-details">
            Danh sách tự động • 2024
            <br />
            49 bài hát • Hơn 4 giờ
        </div>
        <div class="likesong-description">
            Nhạc mà bạn nhấn nút thích trong ứng dụng YouTube sẽ xuất hiện ở đây. Bạn có thể tha...
        </div>
        <div class="likesong-controls">
            <i class="fas fa-random">
            </i>
            <i class="fas fa-play-circle">
            </i>
            <i class="fas fa-ellipsis-h">
            </i>
        </div>
    </div>
    <div class="likesong-main-content">
        <ul class="likesong-song-list">
            <li class="likesong-song">
                <div class="likesong-song-info">
                    <img alt="Album cover" height="50" src="https://storage.googleapis.com/a1aa/image/ILg8mns8f80xIqeBqAqHT3k03TuoME95LkbCPeNiyj4MpfNPB.jpg" width="50" />
                    <div>
                        <div class="likesong-song-title">
                            BÍCH THƯỢNG QUAN x VẠN SỰ TÙY DUYÊN - THANH HƯ...
                        </div>
                        <div class="likesong-song-artist">
                            Đức Tư Remix
                        </div>
                    </div>
                </div>
                <div class="likesong-song-like">
                    <i class="fas fa-thumbs-up">
                    </i>
                    <div>
                        6:11
                    </div>
                </div>
            </li>
            <!-- Các bài hát khác giữ nguyên -->
        </ul>
    </div>
</div>
@endsection