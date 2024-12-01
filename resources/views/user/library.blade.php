@extends('user.layout')

@section('title', 'Library')

@push('styles')
    @vite('resources/css/library.css')
@endpush

@section('content')
    <div class="p-4">
        <h2 class="text-3xl font-bold mb-4">
            Librarys
        </h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex flex-col items-center">
                <img alt="Liked music icon" class="w-24 h-24 rounded-lg cursor-pointer" height="100"
                    src="https://storage.googleapis.com/a1aa/image/1WXOj4TWAUI0LJcJdK3fUOms0x8phjxkvb5qpFaIcJN4Zw5JA.jpg"
                    width="100" />
                <span class="mt-2 cursor-pointer">
                    Liked music
                </span>
                <span class="text-gray-400">
                    Auto List
                </span>
            </div>
            <div class="flex flex-col items-center cursor-pointer">
                <img alt="Rap playlist cover" class="w-24 h-24 rounded-lg cursor-pointer" height="100"
                    src="https://storage.googleapis.com/a1aa/image/9qspU7uk9yqCJ5dWUy5geXbUOz5novf4SoEcAR6lBaVxzgzTA.jpg"
                    width="100" />
                <span class="mt-2 cursor-pointer">
                    Rap
                </span>
                <span class="text-gray-400">
                    Playlist
                </span>
            </div>
            <div class="flex flex-col items-center">
                <img alt="My playlist cover" class="w-24 h-24 rounded-lg cursor-pointer" height="100"
                    src="https://storage.googleapis.com/a1aa/image/UcVJG0Kxs8qcNBfegD9W4JkrTQVF0tNfzLgkqITlIPHVnBnnA.jpg"
                    width="100" />
                <span class="mt-2 cursor-pointer">
                    My playlist
                </span>
                <span class="text-gray-400">
                    Playlist
                </span>
            </div>
            <div class="flex flex-col items-center cursor-pointer">
                <img alt="Artist HIEUTHUHAI" class="w-24 h-24 rounded-full cursor-pointer" height="100"
                    src="https://storage.googleapis.com/a1aa/image/Fx1eHLTcZ1WgQq9RbfMCEtH0xysQNaNZchah7dgGaryuzgzTA.jpg"
                    width="100" />
                <span class="mt-2 cursor-pointer">
                    HIEUTHUHAI
                </span>
                <span class="text-gray-400">
                    Artist
                </span>
            </div>
            @foreach ($playlists as $playlist)
            <div class="flex flex-col items-center cursor-pointer">
                <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}" class="flex flex-col items-center">

                    <img alt="{{ $playlist->name }} cover" class="w-24 h-24 rounded-lg cursor-pointer" height="100"
                        src="https://storage.googleapis.com/a1aa/image/9qspU7uk9yqCJ5dWUy5geXbUOz5novf4SoEcAR6lBaVxzgzTA.jpg"
                        width="100" />
                    <span class="mt-2 cursor-pointer">
                        {{ $playlist->name }}
                    </span>
                    <span class="text-gray-400">
                        Playlist
                    </span>

                </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
