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
                        <th class="px-4 py-2">Artist</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($songs as $song)
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $song->song_name }}</td>
                            <td class="px-4 py-2">{{ $song->author->author_name }}</td>
                            <td class="px-4 py-2 flex space-x-2">
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
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No songs pending approval.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
