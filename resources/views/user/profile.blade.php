<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url('/images/song/dấu chân.png');
            /* Đường dẫn ảnh nền */
            background-size: cover;
            background-position: center;
            background-color: #042355;
            background-attachment: fixed;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
        }

        .overlay {
            background-color: rgba(0, 0, 0, 0.7);
            /* Lớp phủ để làm tối ảnh nền */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            margin-top: 30px;
            position: relative;


        }

        .profile-header,
        .profile-info {
            width: 100%;
            /* Đảm bảo chiều dài bằng nhau */
            max-width: 900px;
            /* Độ rộng tối đa để căn giữa */
            margin: 0 auto;
        }

        .profile-header {
            background-image: url('/images/profile/background.png');
            /* Ảnh nền riêng cho phần header */
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            background-color: #122738;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            position: relative;
            color: #f1f1f1;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 2px solid #f1f1f1;
        }

        .profile-header h2 {
            font-size: 24px;
            margin-bottom: 0.5rem;
        }

        .profile-header .edit-btn,
        .profile-header .premium-btn {
            margin-top: 0.5rem;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .profile-header .edit-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #1db954;
        }

        .profile-header .premium-btn {
            background-color: #ffb800;
            font-weight: bold;
            transition: background-color 0.3s;
            width: 200px;
        }

        .profile-header .premium-btn:hover {
            background-color: #6a5007;
            transition: 0.1s;
        }

        .profile-info {
            background-color: #1c1c1c;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .stat-box {
            background-color: #282828;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .stat-box:hover {
            background-color: #333;
        }

        .playlist-item {
            background-color: #282828;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
            transition: transform 0.3s, background-color 0.3s;
        }

        .playlist-item img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: opacity 0.3s;
        }

        .playlist-item:hover {
            background-color: #333;
            transform: scale(1.05);
        }

        .playlist-item h6 {
            color: #f1f1f1;
        }

        .google-login-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4285f4;
            /* Màu nền xanh giống Premium */
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            /* Chiều rộng giống nút Premium */
        }

        .google-login-btn:hover {
            background-color: #042355;
            /* Màu nền đậm hơn khi hover */
            transition: 0.1s;
        }

        .google-login-btn i {
            margin-right: 10px;
        }

        /* Ẩn form mặc định */
        #change-password-form {
            display: none;
            /* Ban đầu ẩn */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            /* Đảm bảo form nổi bật trên các phần khác */
            width: 300px;
            text-align: center;
            backdrop-filter: brightness(0.5);
        }

        /* Kiểu cho các input trong form */
        #change-password-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Kiểu cho nút */
        #change-password-form button {
            padding: 10px 20px;
            margin: 5px 0;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        /* Kiểu cho nút Đóng */
        #change-password-form button[type="button"] {
            background-color: #f44336;
            color: white;
        }

        /* Kiểu cho nút Submit */
        #change-password-form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        /* Nút Đổi Mật Khẩu */
        .change-password-btn {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            margin: 20px 0;
        }

        /* Kiểu cho nút quay về trang Home */
        .back-to-home-btn {
            display: table-cell;
            padding: 5px 5px;
            margin-top: 20px;
            background-color: #122738;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }

        .back-to-home-btn:hover {
            background-color: #0a1b34;
            /* Màu nền khi hover */
        }
    </style>
    <script>
        // Mở form đổi mật khẩu
        function showChangePasswordForm() {
            document.getElementById('change-password-form').style.display = 'block';
        }

        // Đóng form đổi mật khẩu
        function hideChangePasswordForm() {
            document.getElementById('change-password-form').style.display = 'none';
        }
    </script>
</head>

<body>
    <!-- Lớp phủ tối cho ảnh nền -->
    <div class="overlay"></div>

    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">

            <button onclick="showChangePasswordForm()" class="edit-btn">Đổi mật khẩu</button>

            <!-- Nút quay về trang Home --> <a href="{{ route('home') }}" class="back-to-home-btn">Trở về</a>

            <img src="data:image/jepg;base64,{{$information['img']}}" alt="User Avatar">
            <h2>{{ $information['name'] }}</h2>
            <p>Email: {{ $information['email'] }}</p>

            <!-- Nút Đăng ký Premium -->
            <button class="premium-btn"><a class="premium-btn" href="/premium"> Đăng ký Premium </a></button>

            <div class="container mt-2 ">
                <!-- Nút Đăng nhập bằng Google với thiết kế giống nút Premium -->
                <button class="google-login-btn mx-auto" href="{{ route('link-google') }}">
                    <i class="fa-brands fa-google"></i>
                    Đăng nhập bằng Google
                </button>
            </div>
        </div>


        <!-- Profile Information
        <div class="profile-info mt-4 p-3">
            <div class="row text-center">
                <div class="col-md-4 stat-box">
                    <h5>Liked Songs</h5>
                    <p>120</p>
                </div>
                <div class="col-md-4 stat-box">
                    <h5>Playlists</h5>
                    <p>5</p>
                </div>
                <div class="col-md-4 stat-box">
                    <h5>Followers</h5>
                    <p>320</p>
                </div>
            </div>
        </div> -->

        <!-- User Playlists -->
        <h4 class="mt-4">Your Playlists</h4>
        <div class="row">
            <div class="col-md-3">
                <div class="playlist-item">
                    <img src="{{ asset('images/song/exit.jpg') }}" alt="Playlist Cover">
                    <h6>HIEUTHUHAI</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="playlist-item">
                    <img src="{{ asset('images/song/Obito.jpg') }}" alt="Playlist Cover">
                    <h6>OBITO</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="playlist-item">
                    <img src="{{ asset('images/song/sóng.jpg') }}" alt="Playlist Cover">
                    <h6>SON TUNG MTP</h6>
                </div>
            </div>
            <div class="col-md-3">
                <div class="playlist-item">
                    <img src="{{ asset('images/song/bầu trời.jpg') }}" alt="Playlist Cover">
                    <h6>BTS</h6>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Đổi Mật Khẩu (Pop-up) -->
    <div id="change-password-form" style="display: none;">
        <form action="" method="POST">
            @csrf
            <h2>Đổi Mật Khẩu</h2>

            <!-- Mật khẩu mới -->
            <input type="password" name="new_password" placeholder="Mật khẩu mới" required>

            <!-- Xác nhận mật khẩu -->
            <input type="password" name="new_password_confirmation" placeholder="Xác nhận mật khẩu" required>

            <!-- Nút Submit -->
            <button class="bg-info text-white" type="submit">Đổi mật khẩu</button>

            <!-- Nút Đóng -->
            <button type="button" onclick="hideChangePasswordForm()">Đóng</button>
        </form>






        <!-- Google Sign-In API -->
        <script src="https://accounts.google.com/gsi/client" async defer></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/gg.js"></script>
</body>

</html>
