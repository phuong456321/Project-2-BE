@extends('user.layout')

@section('title', 'Library')


@section('content')
    <div class="p-4">
        <h2 class="text-3xl font-bold mb-4 relative top-1">
            Librarys
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 relative top-20">
            @foreach ($playlists as $playlist)
            <div class="flex flex-col items-center cursor-pointer">
                <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}"
                    class="flex flex-col items-center no-underline">
                    @if ($playlist->name == 'Liked music')
                        <img alt="{{ $playlist->name }} cover"
                            class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-lg cursor-pointer"
                            src="{{ asset('images/like-playlist.webp') }}" />
                    @else
                        <div class="grid grid-cols-2 gap-1 w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28">
                            @php
                                $songslist = $playlist->songs->take(4); // Lấy tối đa 4 bài hát từ playlist
                                $songCount = $songslist->count(); // Số bài hát trong playlist
                            @endphp
                            @foreach ($songslist as $song)
                                <img alt="{{ $song->name }} cover" class="w-full h-full rounded-lg"
                                     src="/image/{{ $song->img_id }}" />
                            @endforeach
            
                            <!-- Hiển thị biểu tượng trống nếu thiếu bài hát -->
                            @for ($i = $songCount; $i < 4; $i++)
                                <div class="bg-gray-500 flex justify-center items-center">
                                    <svg class="h-8 w-8 text-cyan-400" role="img" aria-hidden="true" fill="currentColor"
                                         viewBox="0 0 24 24">
                                        <path
                                            d="M6 3h15v15.167a3.5 3.5 0 1 1-3.5-3.5H19V5H8v13.167a3.5 3.5 0 1 1-3.5-3.5H6V3zm0 13.667H4.5a1.5 1.5 0 1 0 1.5 1.5v-1.5zm13 0h-1.5a1.5 1.5 0 1 0 1.5 1.5v-1.5z">
                                        </path>
                                    </svg>
                                </div>
                            @endfor
                        </div>
                    @endif
            
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

