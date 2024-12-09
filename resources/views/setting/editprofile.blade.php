@extends('setting.layoutsetting')

@section('title', 'Thông tin tài khoản')

@section('content')

<body class="bg-white text-black">
    <div class="container mx-auto p-4 bg-gray-900 text-gray-300 mt-10">
        <h1 class="text-4xl font-bold mb-6 text-white">Chỉnh sửa hồ sơ</h1>
        <form class="space-y-6" action="{{ route('updateprofile') }}" method="POST">
            @csrf
            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-400">Ảnh đại diện</label>
                <div class="flex items-center mt-2 space-x-4">
                    <img
                        src="{{ url('image/' . $user->avatar_id) }}"
                        alt="Avatar"
                        class="w-16 h-16 rounded-full object-cover border border-gray-700">
                    <input
                        type="file"
                        id="avatar" name="avatar"
                        class="block w-full sm:w-70 text-sm text-gray-400 border border-gray-700 cursor-pointer bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <!-- Change Password Button -->
                <div class="flex justify-end mt-4">
                    <a href="{{ route('password.email') }}"
                        class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600">
                        Đổi mật khẩu
                    </a>
                </div>
            </div>

            <!-- Editable Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-400">Họ và tên</label>
                <input value="{{ $user->name }}"
                    type="text"
                    id="name" name="name"
                    class="w-full bg-gray-800 text-gray-300 border border-gray-700 rounded p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập họ và tên của bạn">
            </div>

            <!-- Editable Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-400">Giới thiệu</label>
                <textarea
                    id="bio" name="bio"
                    class="w-full bg-gray-800 text-gray-300 border border-gray-700 rounded p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Viết một chút về bản thân bạn...">{{ $user->author->bio }}</textarea>
            </div>

            <!-- Google Link Section -->
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-400 mb-2">Liên kết Google</h3>
                @if ($user->google_id != null)
                <!-- Nếu đã liên kết -->
                <div id="google-linked">
                    <p class="text-sm text-green-500">Đã liên kết Google</p>
                </div>
                @endif
                <!-- Nếu chưa liên kết -->
                @if ($user->google_id == null)
                <div id="google-not-linked">
                    <p class="text-sm text-gray-400">Bạn chưa liên kết Google.</p>
                    <button type="button" onclick="window.location.href='{{ route('login-google') }}'"
                        class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                        <i class="fa-brands fa-google"></i>
                        Liên kết với Google
                    </button>
                </div>
                @endif
            </div>

            <!-- Save and Cancel Buttons -->
            <div class="flex justify-between items-center mt-6">
                <button
                    type="submit"
                    class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</body>


@endsection