@extends('setting.layoutsetting')

@section('title', 'Song List')

@section('content')

    <h1 class="text-3xl font-bold mb-6 text-gray-100 mt-5">Uploaded Song List</h1>
    <div class="w-full overflow-x-auto">
        <table class="min-w-[800px] table-auto bg-gray-800 shadow-md rounded-lg">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Select</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Song Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Genre</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Area</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Play Count</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Likes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($songs as $item)
                    <tr class="border-b border-gray-700" data-song-id="{{ $item->id }}">
                        <td class="px-4 py-4">
                            <input type="checkbox" class="song-checkbox">
                        </td>
                        <td class="px-4 py-4 text-gray-100 font-medium">{{ $item->song_name }}</td>
                        <td class="px-4 py-4 text-gray-400">{{ $item->genre->name }}</td>
                        <td class="px-4 py-4 text-gray-400">{{ $item->area->name }}</td>
                        <td class="px-4 py-4">
                            <span
                                class="inline-block px-3 py-1 text-xs font-semibold text-green-300 bg-green-900 rounded-full">{{ $item->status }}</span>
                        </td>
                        <td class="px-4 py-4 text-gray-400">{{ $item->play_count }}</td>
                        <td class="px-4 py-4 text-gray-400">{{ $item->likes }}</td>
                    </tr>
                @endforeach
                <!-- Add more rows if necessary -->
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-800" onclick="removeSelectedSongs()">Remove
            Selected Songs</button>
    </div>
    <style>
        @media (max-width: 768px) {
            .px-4 {
                padding-left: 2px;
                padding-right: 2px;
            }

            .py-4 {
                padding-top: 2px;
                padding-bottom: 2px;
            }

            .h-12 {
                height: 48px;
                width: 48px;
            }

            .table-auto {
                width: 100%;
                border-collapse: collapse;
            }
        }
    </style>
    <script>
        function removeSelectedSongs() {
            const selectedSongs = document.querySelectorAll('.song-checkbox:checked');
            const songIds = [];

            // Lấy tất cả các ID của bài hát đã chọn
            selectedSongs.forEach(song => {
                const songId = song.closest('tr').getAttribute('data-song-id');
                songIds.push(songId);
            });

            // Kiểm tra xem có bài hát nào được chọn không
            if (songIds.length > 0) {
                // Gửi AJAX request để xóa các bài hát đã chọn
                fetch("{{ route('songs.removeSelected') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Đảm bảo gửi token CSRF
                        },
                        body: JSON.stringify({
                            song_ids: songIds
                        }) // Gửi các ID bài hát
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Nếu xóa thành công, xóa các hàng đã chọn khỏi bảng
                            selectedSongs.forEach(song => {
                                const row = song.closest('tr');
                                row.remove();
                            });
                            window.location.reload();
                        } else {
                            console.error('Error removing songs:', data.message); // Ghi lỗi vào console
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error); // Ghi lỗi vào console nếu có lỗi trong request
                    });
            } else {
                console.error('No songs selected'); // Ghi lỗi nếu không có bài hát nào được chọn
            }
        }
    </script>

@endsection
