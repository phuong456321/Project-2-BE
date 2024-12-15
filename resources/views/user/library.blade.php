@extends('user.layout')

@section('title', 'Library')
@vite('resources/css/style.css')

@section('content')
<div class="p-4">
    <h2 class="text-3xl font-bold mb-4 relative top-1">
        Librarys
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 relative top-20">
        @foreach ($playlists as $playlist)
            <div class="flex flex-col items-center cursor-pointer">
                <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}" class="flex flex-col items-center no-underline">
                    <img alt="{{ $playlist->name }} cover" class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-lg cursor-pointer" 
                        src="{{ $playlist->name == 'Liked music' ? 'https://i1.sndcdn.com/artworks-4Lu85Xrs7UjJ4wVq-vuI2zg-t500x500.jpg' : 'http://localhost:8000/images/profile/logo-home.png' }}" />
                    <span class="mt-2 cursor-pointer text-center text-black text-xs sm:text-sm md:text-base dark:text-white">
                        {{ $playlist->name }}
                    </span>
                    <span class="text-gray-700 text-xs sm:text-sm dark:text-gray-400">
                        {{ $playlist->name == 'Liked music' ? 'Auto List' : 'Playlist' }}
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
@extends('components.footer')
