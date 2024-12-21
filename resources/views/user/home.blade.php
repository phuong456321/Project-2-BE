@extends('user.layout')
@push('styles')
    <style>
        .search-form input[type="text"] {
            height: 2rem;
        }

        .search-song-icon i {
            position: relative;
            left: 0;
            top: 0;
            font-size: 20px;
            color: #f5efef;
            cursor: pointer;
        }

        .search-song-icon {
            left: -3rem;
        }

        .search-form input[type="text"] {
            height: 2.5rem;
        }

        /* Thêm vào file CSS nếu không dùng plugin */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
@endpush
@section('content')
    <div class="mt-10 prevalent song-item cursor-pointer" data-song-id="{{ $topSongs[0]->id }}">
        <div class="info">
            <h1 class="text-white">
                Prevalent
            </h1>
            <p id="song-name" class="text-white text-2xl">
                {{ $topSongs[0]->song_name }}
            </p>
            <p id="song-artist" class="text-white text-xl">
                {{ $topSongs[0]->author->author_name }}
            </p>
            <audio src="{{ url('storage/' . $topSongs[0]->audio_path) }}" preload="auto" style="display:none;"
                controls></audio>
            <p id="lyrics-text" class="whitespace-pre-line hidden"> {{ $topSongs[0]->lyric }} </p>
            <button>
                Listen now
            </button>
        </div>

        <div class="image-slider rounded-lg flex items-center justify-center">
            <img src="{{ url('image/' . $topSongs[0]->img_id) }}" alt="Song Image" class="song-image">
        </div>
    </div>

    @if (count($playlists) > 0)
    <div class="playlists mt-5">
        <h3 class="text-2xl font-bold mb-4">
            Playlists for you
        </h3>
        <div class="flex overflow-x-auto scrollbar-hide scroll-smooth gap-4 space-x-4">
            @foreach ($playlists as $playlist)
                <div class="playlist cursor-pointer flex text-center flex-shrink-0">
                    <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}" class="flex flex-col items-center text-white no-underline">
                        <div class="images h-28 w-28 lg:h-36 lg:w-36 flex items-center justify-center overflow-hidden">
                            @if ($playlist->name == 'Liked music')
                                <img src="{{ asset('images/like-playlist.webp') }}" alt="Default Image" class="song-image w-full h-full object-cover rounded-lg">
                            @else
                                @php
                                    $songs = $playlist->songs->take(4); // Lấy tối đa 4 bài hát từ playlist
                                    $songCount = $songs->count(); // Số bài hát trong playlist
                                @endphp
                                <div class="grid grid-cols-2 gap-1 w-full h-full">
                                    @foreach ($songs as $song)
                                        <div class="flex justify-center items-center aspect-w-1 aspect-h-1">
                                            <img alt="{{ $song->name }} cover" class="w-full h-auto rounded-lg" src="/image/{{ $song->img_id }}" />
                                        </div>
                                    @endforeach
                                
                                    <!-- Hiển thị biểu tượng trống nếu thiếu bài hát -->
                                    @for ($i = $songCount; $i < 4; $i++)
                                        <div class="bg-gray-500 flex justify-center items-center aspect-w-1 aspect-h-1 h-full">
                                            <svg class="h-8 w-8 text-cyan-400" role="img" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 3h15v15.167a3.5 3.5 0 1 1-3.5-3.5H19V5H8v13.167a3.5 3.5 0 1 1-3.5-3.5H6V3zm0 13.667H4.5a1.5 1.5 0 1 0 1.5 1.5v-1.5zm13 0h-1.5a1.5 1.5 0 1 0 1.5 1.5v-1.5z"></path>
                                            </svg>
                                        </div>
                                    @endfor
                                </div>                                
                            @endif
                        </div>
                        <h3 class="mb-[10px] w-full truncate text-black dark:text-white">{{ $playlist->name }}</h3>
                    </a>
                </div>
            @endforeach
        </div>
    </div>       
    @endif
    @if (!empty($recommendedSongs) && count($recommendedSongs) > 0)
        <div class="trending-music mt-5">
            <h3 class="text-2xl font-bold mb-4">
                Recommended Music
            </h3>
            <div class="flex overflow-x-auto scrollbar-hide scroll-smooth gap-4 space-x-4">
                @foreach ($recommendedSongs as $song)
                    <div class="music song-item cursor-pointer inline-block text-center flex-shrink-0"
                        data-song-id="{{ $song->id }}">
                        <img alt="{{ $song->song_name }}" height="150" src="{{ url('image/' . $song->img_id) }}"
                            class="h-28 w-28 lg:h-36 lg:w-36 object-cover rounded-lg" />
                        <p id="song-name">
                            {{ $song->song_name }}
                        </p>
                        <p id="song-artist">
                            {{ $song->author->author_name }}
                        </p>
                        <audio src="{{ url('storage/' . $song->audio_path) }}" preload="auto" style="display:none;"
                            controls></audio>
                        <p id="lyrics-text" class="hidden whitespace-pre-line"> {{ $song->lyric }} </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if (count($topSongs) > 0)
        <div class="trending-music mt-5">
            <h3 class="text-2xl font-bold mb-4">
                Trending Music
            </h3>
            <div class="flex overflow-x-auto scrollbar-hide scroll-smooth gap-4 space-x-4">
                @foreach ($topSongs as $song)
                    <div class="music song-item cursor-pointer inline-block text-center flex-shrink-0"
                        data-song-id="{{ $song->id }}">
                        <img alt="{{ $song->song_name }}" height="150" src="{{ url('image/' . $song->img_id) }}"
                            class="h-28 w-28 lg:h-36 lg:w-36 object-cover rounded-lg" />
                        <p id="song-name">
                            {{ $song->song_name }}
                        </p>
                        <p id="song-artist">
                            {{ $song->author->author_name }}
                        </p>
                        <audio src="{{ url('storage/' . $song->audio_path) }}" preload="auto" style="display:none;"
                            controls></audio>
                        <p id="lyrics-text" class="hidden  whitespace-pre-line "> {{ $song->lyric }} </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if (count($randomsongs) > 0)
        <div class="mt-5">
            <h3 class="text-2xl font-bold mb-4">Music</h3>
            <div class="w-full overflow-x-auto scrollbar-thin scrollbar-thumb-gray-500 scrollbar-track-gray-200">
                <div class="grid grid-rows-4 grid-flow-col gap-4 min-w-max p-4 sm:grid-rows-2 lg:grid-rows-4">
                    <!-- Bài hát 1 -->
                    @foreach ($randomsongs as $song)
                        <div class="flex items-center gap-4 song-item cursor-pointer" data-song-id="{{ $song->id }}">
                            <img alt="Album cover" class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                                src="{{ url('image/' . $song->img_id) }}" />
                            <div>
                                <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                                    {{ $song->song_name }}
                                </h3>
                                <p class="text-xs sm:text-sm">
                                    {{ $song->author->author_name }} •
                                    @if ($song->play_count < 1000)
                                        {{ $song->play_count }} Plays
                                    @elseif ($song->play_count >= 1000000)
                                        {{ number_format($song->play_count / 1000000, 1) }}M Plays
                                    @else
                                        {{ number_format($song->play_count / 1000, 1) }}K Plays
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Overlay Login Form -->
    <div id="loginOverlay" class="overlay" style="display: none;">
        <div class="login-form">
            <span onclick="closeOverlay()"
                style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
            <h2>Welcome</h2>
            <h3>Login into your account</h3>

            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}"
                onclick="window.location.href='{{ route('login-google') }}'" alt="gg">

            <div class="separator">
                <hr> <span>Or continue with</span>
                <hr>
            </div>
            @if (session('message'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showLoginForm(); // Hiển thị form đăng nhập
                    });
                </script>
                <div class="alert alert-danger" id="login-message">{{ session('message') }}</div>
            @endif
            <form id="login-form" action="{{ route('login') }}" method="POST">
                @csrf
                <input type="text" placeholder="Email" @error('email') is-invalid @enderror id="email"
                    name="email" value="{{ old('email') }}" autocomplete="email">
                @error('email')
                    <p class="invalid-feedback">{{ $message }}</p>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showLoginForm(); // Hiển thị form đăng nhập
                        });
                    </script>
                @enderror
                <input type="password" placeholder="Password" @error('password') is-invalid @enderror id="password"
                    name="password" value="{{ old('password') }}" autocomplete="current-password">
                @error('password')
                    <p class="invalid-feedback">{{ $message }}</p>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showLoginForm(); // Hiển thị form đăng nhập
                        });
                    </script>
                @enderror


                <div class="options">
                    <label>
                        <input type="checkbox" name="remember" id="remember"> Remember me
                    </label>
                    <a href="{{ route('password.forgot') }}" class="recover-password">Forgot Password?</a>
                </div>
                <button class="action-btn" type="submit">Log In</button>
            </form>
            <p>
                Don't have an account yet?
                <a href="javascript:void(0)" onclick="showRegisterForm()">Register</a>
            </p>

        </div>
    </div>
    <div id="registerOverlay" class="overlay" style="display: none;">

        <div class="form-container">
            <span onclick="closeOverlay()"
                style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 20px;">&times;</span>
            <h2>Create Account</h2>
            <p>Register a new account</p>
            <img class="gg-btn" src="{{ asset('images/profile/gg.png') }}"
                onclick="window.location.href='{{ route('login-google') }}'" alt="gg">

            <div class="separator">
                <hr> <span>Or continue with</span>
                <hr>
            </div>

            <!-- Form đăng ký -->
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
                <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                    autocomplete="new-password">
                <div style="margin: 10px 0;">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">
                        I agree to the
                        <a href="/loginservice" target="_blank">terms and conditions</a>.
                    </label>
                </div>

                <button class="action-btn" type="submit">Register</button>
            </form>

            <p>
                Already have an account?
                <a onclick="showLoginForm()">Login</a>
            </p>
        </div>
    </div>
@endsection
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showLoginForm(); // Hiển thị form đăng nhập
        });
    </script>
@endif
@push('scripts')
    <script>
        recommendedSongs = @json($recommendedSongs ?? []);
        randomsongs = @json($randomsongs ?? []);
    </script>
@endpush
