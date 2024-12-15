@extends('admin.admin')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Song Approval</h1>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-gray-800 p-6 rounded-lg">
            <table class="table-auto w-full text-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Song Name</th>
                        <th class="px-4 py-2">Author</th>
                        <th class="px-4 py-2">Restricted Tracks</th>
                        <th class="px-4 py-2">Audio</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($songs as $song)
                        <tr class="border-t border-gray-700">
                            <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $song->song_name }}</td>
                            <td class="px-4 py-2 text-center">{{ $song->author->author_name }}</td>
                            <td class="px-4 py-2 text-center">{{ $song->author->restricted_tracks_count }}</td>
                            <td class="px-4 py-2 text-center">
                                <!-- Thêm trình phát âm thanh sử dụng dash.js -->
                                @if ($song->audio_path)
                                    <audio id="audio-player-{{ $song->id }}" class="audio-player" controls></audio>

                                    <script>
                                        const player{{ $song->id }} = new dashjs.MediaPlayer().create();
                                        player{{ $song->id }}.initialize(document.querySelector("#audio-player-{{ $song->id }}"),
                                            "/storage/{{ $song->audio_path }}", false);
                                    </script>
                                @else
                                    No audio available
                                @endif
                            </td>
                            <td class="px-4 py-2 flex space-x-2 justify-center">
                                <form action="{{ route('admin.songApproval.approve', $song->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.songApproval.reject', $song->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                        Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @if ($song->related_songs->count() > 0)
                            <tr>
                                <td colspan="6" class="text-center py-4 bg-gray-600">Bài hát trùng lặp</td>
                            </tr>
                            @foreach ($song->related_songs as $related_song)
                                <tr class="bg-gray-600">
                                    <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2">{{ $related_song->song_name }}</td>
                                    <td class="px-4 py-2 text-center">{{ $related_song->author->author_name }}</td>
                                    <td></td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($related_song->audio_path)
                                            <audio id="audio-player-{{ $related_song->id }}" class="audio-player" controls></audio>
                                            <script>
                                                const player{{ $related_song->id }} = new dashjs.MediaPlayer().create();
                                                player{{ $related_song->id }}.initialize(document.querySelector("#audio-player-{{ $related_song->id }}"),
                                                    "/storage/{{ $related_song->audio_path }}", false);
                                            </script>
                                        @else
                                            No audio available
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-4 bg-gray-600">Không tìm thấy bài hát trùng lặp</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No songs pending approval.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
