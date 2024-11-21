<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music</title>
    @vite('resources/css/style.css')
    @vite('resources/css/nowPlay.css')
    @vite('resources/css/sideBarStyle.css')
    @vite('resources/css/audio.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @stack('styles')

</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo">
                </a>
            </div>
            <nav class="menu">
                <ul>
                    <li class="{{ request()->is('home') ? 'active' : '' }}">
                        <img src="{{ asset('icon/home-svgrepo-com.svg') }}" class="menu-icon">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="{{ request()->is('discover') ? 'active' : '' }}">
                        <img src="{{ asset('icon/discover-home.svg') }}" class="menu-icon">
                        <a href="/discover">Discover</a>
                    </li>
                    <li class="{{ request()->is('library') ? 'active' : '' }}">
                        <img src="{{ asset('icon/library-home.svg') }}" class="menu-icon">
                        <a href="/library">Librarys</a>
                    </li>
                    <li class="{{ request()->is('profile') ? 'active' : '' }}">
                        <img src="{{ asset('icon/profile-home.svg') }}" class="menu-icon">
                        <a href="/profile" class="profile-btn">Profile</a>
                    </li>
                </ul>
            </nav>
            <hr>
            <div class="my-music">
                <button>Create playlist</button>
                <!-- <ul>
                    <li>Liked songs</li>
                    <li>Albums</li>
                    <li>Playlists1</li>
                    <li>Playlists2</li>
                </ul> -->
            </div>
            <div class="settings">
                <ul>
                    <li>Setting</li>
                    <li><a href="">Log out</a></li>
                </ul>
            </div>
        </aside>
        <main class="content">
            @yield('content') <!-- Nội dung động -->
        </main>
    </div>
</body>

</html>
