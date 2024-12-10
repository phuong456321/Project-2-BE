@extends('user.layout')

@section('title', 'Library')
@vite('resources/css/style.css')

@section('content')
    <div class="p-4">
        <h2 class="text-3xl font-bold mb-4">
            Librarys
        </h2>
        <div class="grid grid-cols-4 gap-4">

            @foreach ($playlists as $playlist)
                <div class="flex flex-col items-center cursor-pointer">
                    <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}" class="flex flex-col items-center">
                        <img alt="{{ $playlist->name }} cover" class="w-24 h-24 rounded-lg cursor-pointer" height="100"
                            src="{{ $playlist->name == 'Liked music' ? 'https://i1.sndcdn.com/artworks-4Lu85Xrs7UjJ4wVq-vuI2zg-t500x500.jpg' : 'http://localhost:8000/images/profile/logo-home.png' }}"
                            width="100" />
                        <span class="mt-2 cursor-pointer">
                            {{ $playlist->name }}
                        </span>
                        <span class="text-gray-400">
                            {{ $playlist->name == 'Liked music' ? 'Auto List' : 'Playlist' }}
                        </span>
                    </a>
                </div>
            @endforeach

        </div>
    </div>
@endsection
@extends('components.footer')
