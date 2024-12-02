@extends('user.layout')

@section('title', 'Library')
@vite('resources/css/style.css')
@section('content')
    <div class="p-4">
        <h2 class="text-3xl font-bold mb-4">
            Librarys
        </h2>
        <div class="grid grid-cols-4 gap-4">

            {{-- <div class="flex flex-col items-center">
                    <img alt="Liked music icon" class="w-24 h-24 rounded-lg cursor-pointer" height="100"
                        src="https://storage.googleapis.com/a1aa/image/1WXOj4TWAUI0LJcJdK3fUOms0x8phjxkvb5qpFaIcJN4Zw5JA.jpg"
                        width="100" />
                    <span class="mt-2 cursor-pointer">
                        Liked music
                    </span>
                    <span class="text-gray-400">
                        Auto List
                    </span>
                </div> --}}

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
