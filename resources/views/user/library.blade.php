@extends('user.layout')

@section('title', 'Library',)

@push('styles')
    @vite('resources/css/library.css')
@endpush


@section('content')

<body class="bg-gray-800 text-white">
    <div class="header">
        <input placeholder="Bạn đang tìm kiếm gì?" type="text" />
        @if (Auth::check())
        {{-- Nếu người dùng đã đăng nhập --}}
        <div id="avatar" class="user" onclick="togglePopup()">
            <span>{{ Auth::user()->name }}</span>
            <img alt="User Avatar" class="rounded-full" height="40"
                src="data:image/jepg;base64,{{ Auth::user()->avatar_id ? App\Models\Image::where('img_id', Auth::user()->avatar_id)->first()->img_path ?? asset('images/default-avatar.jpg') : asset('images/default-avatar.jpg') }}"
                width="40" />
        </div>
        @endif
    </div>
    <div class="p-4">
        <h2 class="text-3xl font-bold mb-4">
            Librarys
        </h2>
        <div class="grid grid-cols-4 gap-4">
            <div class="flex flex-col items-center">
                <img alt="Liked music icon" class="w-24 h-24 rounded-lg cursor-pointer" height="100" src="https://storage.googleapis.com/a1aa/image/1WXOj4TWAUI0LJcJdK3fUOms0x8phjxkvb5qpFaIcJN4Zw5JA.jpg" width="100" />
                <span class="mt-2 cursor-pointer">
                    Liked music
                </span>
                <span class="text-gray-400">
                    Auto List
                </span>
            </div>
            <div class="flex flex-col items-center cursor-pointer">
                <img alt="Rap playlist cover" class="w-24 h-24 rounded-lg cursor-pointer" height="100" src="https://storage.googleapis.com/a1aa/image/9qspU7uk9yqCJ5dWUy5geXbUOz5novf4SoEcAR6lBaVxzgzTA.jpg" width="100" />
                <span class="mt-2 cursor-pointer">
                    Rap
                </span>
                <span class="text-gray-400">
                    Playlist
                </span>
            </div>
            <div class="flex flex-col items-center">
                <img alt="My playlist cover" class="w-24 h-24 rounded-lg cursor-pointer" height="100" src="https://storage.googleapis.com/a1aa/image/UcVJG0Kxs8qcNBfegD9W4JkrTQVF0tNfzLgkqITlIPHVnBnnA.jpg" width="100" />
                <span class="mt-2 cursor-pointer">
                    My playlist
                </span>
                <span class="text-gray-400">
                    Playlist
                </span>
            </div>
            <div class="flex flex-col items-center cursor-pointer">
                <i class="fas fa-filter text-2xl">
                </i>
                <span class="mt-2 cursor-pointer">
                    Filter
                </span>
            </div>
            <div class="flex flex-col items-center cursor-pointer">
                <img alt="Artist HIEUTHUHAI" class="w-24 h-24 rounded-full cursor-pointer" height="100" src="https://storage.googleapis.com/a1aa/image/Fx1eHLTcZ1WgQq9RbfMCEtH0xysQNaNZchah7dgGaryuzgzTA.jpg" width="100" />
                <span class="mt-2 cursor-pointer">
                    HIEUTHUHAI
                </span>
                <span class="text-gray-400">
                    Artist
                </span>
            </div>
            <div class="flex flex-col items-center cursor-pointer">
                <img alt="Artist Sơn Tùng M-TP" class="w-24 h-24 rounded-full cursor-pointer" height="100" src="https://storage.googleapis.com/a1aa/image/awPf6CSlJkXyfkqlXnEanf2aDeZcuZSc6zk9WnZ3XT3yODOPB.jpg" width="100" />
                <span class="mt-2 cursor-pointer">
                    Sơn Tùng M-TP
                </span>
                <span class="text-gray-400">
                    Artist
                </span>
                <img alt="User" height="40" src="images/profile/hinh tao.jpg" width="40" />
            </div>
        </div>
    </div>
    
</body>

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

                @foreach ($playlists as $playlist)
                    <div class="flex flex-col items-center cursor-pointer">
                        <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}"
                            class="flex flex-col items-center">

                            <img alt="{{ $playlist->name }} cover" class="w-24 h-24 rounded-lg cursor-pointer"
                                height="100"
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

                <div class="flex flex-col items-center cursor-pointer">
                    <img alt="Artist Sơn Tùng M-TP" class="w-24 h-24 rounded-full cursor-pointer" height="100"
                        src="https://storage.googleapis.com/a1aa/image/awPf6CSlJkXyfkqlXnEanf2aDeZcuZSc6zk9WnZ3XT3yODOPB.jpg"
                        width="100" />
                    <span class="mt-2 cursor-pointer">
                        Sơn Tùng M-TP
                    </span>
                    <span class="text-gray-400">
                        Artist
                    </span>
                </div>

            </div>
        </div>
    </body>

@endsection
