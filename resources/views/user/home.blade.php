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
    <div class="flex overflow-x-auto scrollbar-hide scroll-smooth gap-4 space-x-4 ">
        @foreach ($playlists as $playlist)
        <div class="playlist cursor-pointer inline-block text-center flex-shrink-0 ">
            <a href="{{ route('playlist', ['playlist_id' => $playlist->id]) }}"
                class="flex flex-col items-center text-white no-underline">
                <div class="images h-28 w-28 lg:h-36 lg:w-36 flex items-center justify-center ">
                    @if ($playlist->name == 'Liked music')
                    <img src="https://i1.sndcdn.com/artworks-4Lu85Xrs7UjJ4wVq-vuI2zg-t500x500.jpg"
                        alt="Default Image" class="song-image w-full h-full object-cover rounded-lg">
                    @elseif ($playlist->songs->isEmpty())
                    <img src="http://localhost:8000/images/profile/logo-home.png" alt="Default Image"
                        class="song-image w-full h-full object-cover rounded-lg">
                    @else
                    @foreach ($playlist->songs as $song)
                    <img src="{{ url('image/' . $song->img_id) }}" alt="Song Image"
                        class="song-image w-full h-full object-cover rounded-lg">
                    @endforeach
                    @endif
                </div>
                <h3 class="mb-[10px] w-full truncate text-black dark:text-white ">{{ $playlist->name }}</h3>
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
@if (count($songs) > 0)
<div class="trending-music mt-5">
    <h3 class="text-2xl font-bold mb-4">
        Music
    </h3>
    <div class="flex overflow-x-auto scrollbar-hide scroll-smooth gap-4 space-x-4">
        @foreach ($songs as $song)
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
<div class="mb-6">
    <h2 class="text-2xl font-bold mb-4">Music</h2>
    <div class="w-full overflow-x-auto">
        <div
            class="grid grid-rows-4 grid-flow-col gap-4 min-w-max p-4 sm:grid-rows-2 lg:grid-rows-4">
            <!-- Bài hát 1 -->
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/Q0I8OgnNQbb1Bx9rqoXzWonG4Nml4bOzLXtumcZ2wjfQuu9JA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Dưới Tán Cây Khô Hoa Nở
                    </h3>
                    <p class="text-xs sm:text-sm">Jack - J97 • 4.9M</p>
                </div>
            </div>

            <!-- Bài hát 2 -->
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/gwvm5rSwpf1QGKy4CtRJTBM1mtaGBTeteqnMCeaHOdp5x1tPB.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Mắt Kết Nối
                    </h3>
                    <p class="text-xs sm:text-sm">Dương Domic • 16</p>
                </div>
            </div>

            <!-- Bài hát 3 -->
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/UKO9z9eePqjXk09Qf4vUYEvlgefrsbIYDIO7mG8Moyd9irbfE.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Chúng Ta Của Hiện Tại
                    </h3>
                    <p class="text-xs sm:text-sm">Sơn Tùng M-TP</p>
                </div>
            </div>

            <!-- Bài hát 4 -->
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img
                    alt="Album cover"
                    class="w-12 h-12 sm:w-16 sm:h-16 rounded"
                    src="https://storage.googleapis.com/a1aa/image/q9E7VrwGTm6iMN89XMeYqglDuAMWVtthWmd62H3paBegcd7TA.jpg" />
                <div>
                    <h3 class="font-bold text-sm sm:text-base lg:text-lg">
                        Exit Sign
                    </h3>
                    <p class="text-xs sm:text-sm">HIEUTHUHAI • 5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="flex justify-end mt-4">
        <button class="bg-gray-800 px-4 py-2 rounded">Phát tất cả</button>
        <button class="bg-gray-800 px-4 py-2 rounded ml-2">
            <i class="fas fa-chevron-right"> </i>
        </button>
    </div> -->
</div>

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
    songs = @json($songs ?? []);
</script>
@endpush