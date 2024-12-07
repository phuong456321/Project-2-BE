@extends('setting.layoutsetting')

@section('title', 'Danh sách bài hát')

@section('content')

<body class="bg-white text-black">
    <h1 class="text-3xl font-bold mb-6 text-gray-100 mt-5">Danh sách bài hát đã tải lên</h1>
    <div class="overflow-x-auto">
        <table class="min-w-100 table-auto bg-gray-800 shadow-md rounded-lg">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Chọn</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Ảnh</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Tên bài hát</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Thể loại</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Khu vực</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Lượt nghe</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-300">Lượt thích</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-700 ">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="song-checkbox">
                    </td>
                    <td class="px-6 py-4">
                        <img src="https://via.placeholder.com/100" alt="Song" class="h-12 w-12 rounded-full object-cover">
                    </td>
                    <td class="px-6 py-4 text-gray-100 font-medium">Tên bài hát</td>
                    <td class="px-6 py-4 text-gray-400">Thể loại</td>
                    <td class="px-6 py-4 text-gray-400">Khu vực</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-green-300 bg-green-900 rounded-full">Published</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">100</td>
                    <td class="px-6 py-4 text-gray-400">50</td>
                </tr>
                <tr class="border-b border-gray-700 ">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="song-checkbox">
                    </td>
                    <td class="px-6 py-4">
                        <img src="https://via.placeholder.com/100" alt="Song" class="h-12 w-12 rounded-full object-cover">
                    </td>
                    <td class="px-6 py-4 text-gray-100 font-medium">Tên bài hát 2</td>
                    <td class="px-6 py-4 text-gray-400">Thể loại</td>
                    <td class="px-6 py-4 text-gray-400">Khu vực</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-yellow-300 bg-yellow-900 rounded-full">Pending</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">50</td>
                    <td class="px-6 py-4 text-gray-400">25</td>
                </tr>
                <tr class="border-b border-gray-700 ">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="song-checkbox">
                    </td>
                    <td class="px-6 py-4">
                        <img src="https://via.placeholder.com/100" alt="Song" class="h-12 w-12 rounded-full object-cover">
                    </td>
                    <td class="px-6 py-4 text-gray-100 font-medium">Tên bài hát</td>
                    <td class="px-6 py-4 text-gray-400">Thể loại</td>
                    <td class="px-6 py-4 text-gray-400">Khu vực</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-green-300 bg-green-900 rounded-full">Published</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">100</td>
                    <td class="px-6 py-4 text-gray-400">50</td>
                </tr>
                <tr class="border-b border-gray-700 ">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="song-checkbox">
                    </td>
                    <td class="px-6 py-4">
                        <img src="https://via.placeholder.com/100" alt="Song" class="h-12 w-12 rounded-full object-cover">
                    </td>
                    <td class="px-6 py-4 text-gray-100 font-medium">Tên bài hát 2</td>
                    <td class="px-6 py-4 text-gray-400">Thể loại</td>
                    <td class="px-6 py-4 text-gray-400">Khu vực</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-yellow-300 bg-yellow-900 rounded-full">Pending</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">50</td>
                    <td class="px-6 py-4 text-gray-400">25</td>
                </tr>
                <!-- Thêm nhiều dòng khác -->
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800" onclick="removeSelectedSongs()">Gỡ bài hát đã chọn</button>
    </div>


    <script>
        function removeSelectedSongs() {
            const selectedSongs = document.querySelectorAll('.song-checkbox:checked'); // Lấy các checkbox đã chọn
            selectedSongs.forEach(song => {
                const row = song.closest('tr'); // Lấy dòng chứa checkbox đã chọn
                row.remove();
            });
        }
    </script>
</body>

@endsection