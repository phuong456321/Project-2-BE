@extends('layout')

@section('title', 'Library')

@push('styles')
    @vite('resources/css/library.css')
@endpush


@section('content')
    <div class="library">
        <section class="playlist-section">
            <h3>Playlists</h3>
            <div class="playlists">
                <div class="playlist-card">
                    <img src="{{ asset('images/song/exit.jpg') }}" alt="Playlist 1" class="playlist-img">
                    <div class="playlist-info">
                        <p class="playlist-name">Ikon</p>
                        <span class="playlist-creator">Quốc Đạt</span>
                    </div>
                </div>
                <div class="playlist-card">
                    <img src="{{ asset('images/song/Obito.jpg') }}" alt="Playlist 2" class="playlist-img">
                    <div class="playlist-info">
                        <p class="playlist-name">ダット</p>
                        <span class="playlist-creator">Quốc Đạt</span>
                    </div>
                </div>
                <!-- Thêm các playlist khác ở đây -->
            </div>
        </section>

        <!-- TABS -->
        <div class="tabs">
            <button class="tab-btn" data-tab="songs">Bài Hát</button>
            <button class="tab-btn" data-tab="albums">Album</button>
            <button class="tab-btn" data-tab="mv">MV</button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <div id="songs" class="tab active">
                <p>Danh sách bài hát...</p>
            </div>
            <div id="albums" class="tab">
                <div class="album-card">
                    <img src="{{ asset('images/album1.jpg') }}" alt="Album 1" class="album-img">
                    <p class="album-name">Album 1</p>
                    <span class="album-artist">Ca sĩ A</span>
                </div>
                <!-- Thêm album ở đây -->
            </div>
            <div id="mv" class="tab">
            </div>
        </div>
    </div>
@endsection
