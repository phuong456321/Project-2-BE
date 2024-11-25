<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nulltifly')</title>
    @vite('resources/css/style.css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    @stack('styles')
    <script src="https://cdn.tailwindcss.com">
    </script>
    <style>
        .sidebar a.active {
            background-color: #444;
            color: white;
            font-weight: bold;
            border-left: 5px solid #00f;
        }

        /* Popup styles */
        .avatar-popup {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 180px;
            padding: 10px 0;
        }

        .avatar-popup ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .avatar-popup ul li a {
            display: block;
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
        }

        .avatar-popup ul li a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <a class="no-hover" href="{{ route('home') }}">
            <img alt="Logo" height="100" src="images/profile/logo-home.png" width="100" />
        </a>
        <a href="{{ route('home') }}" id="home" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{route('library')}}" id="librarys" class="{{ request()->is('library') ? 'active' : '' }}">Library</a>
        <a href="/playist" id="playist" class="{{ request()->is('playist') ? 'active' : '' }}">Playlist</a>
        <div class="create-playlist">
            + Create playlist
        </div>
        <a href="/likesong" class="{{ request()->is('likesong') ? 'active' : '' }}">Like songs</a>
        <a href="#" class="{{ request()->is('') ? 'active' : '' }}">My Album</a>
        <a href="/playist" class="{{ request()->is('playlist1') ? 'active' : '' }}">Playlist1</a>
    </div>
    
     <!-- Avatar Section -->
     <div class="relative">
        <div id="popup" class="avatar-popup hidden">
            <ul>
                <li><a href="/profile" class="block px-4 py-2">Profile</a></li>
                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-content">
        {{-- <div class="header">
            <input id="searchInput" placeholder="Bạn đang tìm kiếm gì?" type="text" />
    
            @if (Auth::check())
                <-- Nếu người dùng đã đăng nhập -->
                <div id="avatar" class="user" onclick="togglePopup()">
                    <span>{{ Auth::user()->name }}</span>
                    <img alt="User Avatar" class="rounded-full" height="40"
                        src="data:image/jepg;base64,{{ Auth::user()->avatar_id ? App\Models\Image::where('img_id', Auth::user()->avatar_id)->first()->img_path ?? asset('images/default-avatar.jpg') : asset('images/default-avatar.jpg') }}"
                        width="40" />
                </div>
    
                <!-- Popup Profile / Logout -->
                <div id="popup" class="avatar-popup hidden">
                    <ul>
                        <li><a href="/profile/{{ Auth::user()->id }}">Profile</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="logout-btn">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <-- Nếu người dùng chưa đăng nhập -->
                <div class="auth-links">
                    <a href="javascript:void(0)" onclick="showLoginForm()" class="login-link">Login</a>
                    <span class="separator">/</span>
                    <a href="javascript:void(0)" onclick="showRegisterForm()" class="register-link">Register</a>
                </div>
            @endif
        </div> --}}
        @yield('content')
    </div>
</body>

</html>