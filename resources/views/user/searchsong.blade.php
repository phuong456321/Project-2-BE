@extends('user.layout')

@push('styles')
@vite('resources/css/searchsong.css')
@endpush

@push('styles')
@vite('resources/css/searchsong.css')
@endpush


@section('content')

<body class="bg-black text-white">
    <div class="container mx-auto p-4">
        <!-- Search Bar -->
        <div class="flex items-center  justify-center mb-4 flex justify-end">
            <input class="bg-gray-800 text-white rounded-full px-4 py-2 w-full" placeholder="bích thượng quan remix" type="text" />
           <div class="icon-search-song">
           <i class="fas fa-search text-gray-400">
           </i>
           </div>
        </div>
        <!-- Navigation Tabs -->
        <div class="flex space-x-4 mb-4">
            <a class="text-white font-bold" href="#">
              NULLTIFLY MUSIC
            </a>
            <a class="text-gray-400" href="/library">
                THƯ VIỆN
            </a>
        </div>
        <!-- Filter Buttons -->
        <div class="flex space-x-2 mb-4">
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Bài hát
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Video
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Đĩa nhạc
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Danh sách phát của cộng đồng
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Nghệ sĩ
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Podcast
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Tập podcast
            </button>
            <button class="bg-gray-800 text-white px-4 py-2 rounded-full">
                Hồ sơ
            </button>
        </div>
        <!-- Top Result -->
        <div class="mb-4">
            <h2 class="text-xl font-bold mb-2">
                Kết quả hàng đầu
            </h2>
            <div class="bg-gray-800 p-4 rounded-lg flex items-center">
                <img alt="Album cover image" class="w-20 h-20 rounded-lg mr-4" height="100" src="https://storage.googleapis.com/a1aa/image/GPF0MNJK7k6wOVL61xubHPlDxNUIffFSaJeugmOGbFUaNNonA.jpg" width="100" />
                <div>
                    <h3 class="text-lg font-bold">
                        BÍCH THƯỢNG QUAN x VẠN SỰ TÙY DUYÊN - THANH HƯNG FT ĐỨC TỪ REMIX- HOT...
                    </h3>
                    <p class="text-gray-400">
                        Video • Đức Tư Remix • 134 N lượt xem • 6:11
                    </p>
                </div>
                <div class="ml-auto flex space-x-2">
                    <button class="bg-white text-black px-4 py-2 rounded-full">
                        Phát
                    </button>
                    <button class="bg-gray-700 text-white px-4 py-2 rounded-full">
                        Lưu
                    </button>
                    <!-- <i class="fas fa-ellipsis-h text-gray-400">
                    </i> -->
                </div>
            </div>
        </div>
        <!-- Songs List -->
        <div>
            <h2 class="text-xl font-bold mb-2">
                Bài hát
            </h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <img alt="Song thumbnail" class="w-12 h-12 rounded-lg mr-4" height="50" src="https://storage.googleapis.com/a1aa/image/GNfcDpFcLpUya63uChyB6reosxqABXzWkFx35jR1DHXsmG0TA.jpg" width="50" />
                    <div>
                        <h3 class="text-lg">
                            Bích thượng quan x Vạn sự tùy duyên
                        </h3>
                        <p class="text-gray-400">
                            Lê An Thái • Bích thượng quan x Vạn sự tùy duyên • 6:11 • 5,4 N lượt phát
                        </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <img alt="Song thumbnail" class="w-12 h-12 rounded-lg mr-4" height="50" src="https://storage.googleapis.com/a1aa/image/GNfcDpFcLpUya63uChyB6reosxqABXzWkFx35jR1DHXsmG0TA.jpg" width="50" />
                    <div>
                        <h3 class="text-lg">
                            Bích Thượng Quan x Vạn Sự Tùy Duyên Đức Tư Remix
                        </h3>
                        <p class="text-gray-400">
                            Đức Tư • Bích Thượng Quan x Vạn Sự Tùy Duyên Đức Tư Remix • 6:24 • 850 lượt phát
                        </p>
                    </div>
                </div>
                <div class="flex items-center">
                    <img alt="Song thumbnail" class="w-12 h-12 rounded-lg mr-4" height="50" src="https://storage.googleapis.com/a1aa/image/GNfcDpFcLpUya63uChyB6reosxqABXzWkFx35jR1DHXsmG0TA.jpg" width="50" />
                    <div>
                        <h3 class="text-lg">
                            壁上观
                        </h3>
                        <p class="text-gray-400">
                            • 6:11 • 5,4 N lượt phát
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection