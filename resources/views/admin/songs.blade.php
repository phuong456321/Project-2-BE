@extends('admin.admin')
@section('head')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
@endsection
@section('content')
    <!-- Main Content -->
    <h1 class="text-2xl font-bold mb-4">Manage Songs</h1>

    <!-- Songs Table -->
    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-white mb-4">Song List</h2>

        <!-- Filter Form -->
        <div class="mb-4 flex flex-col sm:flex-row gap-4">
            <form action="{{ route('admin.songs') }}" method="GET" class="flex flex-wrap gap-4 items-center w-full">
                <!-- Filter by Song Name -->
                <input type="text" name="song_name" placeholder="Search by song name" value="{{ request('song_name') }}"
                    class="px-3 py-2 bg-gray-700 text-white rounded w-full sm:w-auto">

                <!-- Filter by Genre -->
                <select name="genre_id" class="px-3 py-2 bg-gray-700 text-white rounded w-full sm:w-auto">
                    <option value="">All Genres</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Filter by Status -->
                <select name="status" class="px-3 py-2 bg-gray-700 text-white rounded w-full sm:w-auto">
                    <option value="">All Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>

                <!-- Filter Button -->
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full sm:w-auto">
                    Filter
                </button>

                <!-- Clear Filters Button -->
                <div class="flex justify-start sm:justify-end w-full sm:w-auto">
                    <a href="{{ route('admin.songs') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center justify-center w-full sm:w-auto">
                        Clear Filters
                    </a>
                </div>
            </form>
             <!-- Popup Form for Creating New Song -->
    <div x-data="{ open: false }" class="self-end w-full sm:w-auto">
        <!-- Button to open the popup -->
        <button @click="open = true" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 whitespace-nowrap w-full sm:w-auto">
           + Create New Song
        </button>
    
        <!-- Popup -->
        <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-white">Create New Song</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-200">✖</button>
                </div>
    
                <!-- Form -->
                <form action="{{ route('admin.songs') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="song_name" class="block text-white font-bold mb-2">Song Name</label>
                        <input type="text" id="song_name" name="song_name" class="w-full px-3 py-2 bg-gray-700 text-white rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="author_id" class="block text-white font-bold mb-2">Author</label>
                        <select id="author_id" name="author_id" class="w-full px-3 py-2 bg-gray-700 text-white rounded" required>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->author_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-white font-bold mb-2">Description</label>
                        <textarea id="description" name="description" class="w-full px-3 py-2 bg-gray-700 text-white rounded"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="genre_id" class="block text-white font-bold mb-2">Genre</label>
                        <select id="genre_id" name="genre_id" class="w-full px-3 py-2 bg-gray-700 text-white rounded" required>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="img_id" class="block text-white font-bold mb-2">Song Image</label>
                        <input type="file" id="img_id" name="img_id" class="w-full px-3 py-2 bg-gray-700 text-white rounded" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="open = false" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-2">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Create Song
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </div>

        <!-- Songs Table (Make it scrollable on small screens) -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Image</th>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Artist</th>
                        <th class="px-4 py-2 text-left">Genre</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($songs->isEmpty())
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center">No songs found</td>
                        </tr>
                    @else
                        @foreach ($songs as $song)
                            <tr class="border-b border-gray-700">
                                <td class="px-4 py-2 flex justify-center items-center">
                                    <img src="/image/{{ $song->img_id }}" alt="Song Image" class="w-10 h-10 rounded-full"
                                        loading="lazy">
                                </td>
                                <td class="px-4 py-2">{{ $song->song_name }}</td>
                                <td class="px-4 py-2">{{ $song->author ? $song->author->author_name : 'Unknown' }}</td>
                                <td class="px-4 py-2">{{ $song->genre ? $song->genre->name : 'Unknown' }}</td>
                                <td class="px-4 py-2">
                                    <!-- Status Dropdown -->
                                    <form action="{{ route('admin.updateStatus', $song->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="bg-gray-800 text-white rounded px-2 py-1">
                                            <option value="published" {{ $song->status == 'published' ? 'selected' : '' }}>
                                                Published</option>
                                            <option value="deleted" {{ $song->status == 'deleted' ? 'selected' : '' }}>
                                                Deleted</option>
                                            <option value="inactive" {{ $song->status == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                            <option value="pending" {{ $song->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-2">
                                    <!-- Edit Button -->
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = true"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                            Edit
                                        </button>

                                        <!-- Popup -->
                                        <div x-show="open"
                                            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                                            <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                                                <!-- Header -->
                                                <div class="flex justify-between items-center mb-4">
                                                    <h2 class="text-xl font-bold text-white">Edit Song</h2>
                                                    <button @click="open = false" class="text-gray-400 hover:text-gray-200">
                                                        ✖
                                                    </button>
                                                </div>

                                                <!-- Form -->
                                                <form action="{{ route('admin.updateSong', $song->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Song Name -->
                                                    <div class="mb-4">
                                                        <label for="song_name" class="block text-white font-bold mb-2">Song
                                                            Name</label>
                                                        <input type="text" id="song_name" name="song_name"
                                                            value="{{ $song->song_name }}"
                                                            class="w-full px-3 py-2 bg-gray-700 text-white rounded">
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="mb-4">
                                                        <label for="description"
                                                            class="block text-white font-bold mb-2">Description</label>
                                                        <textarea id="description" name="description" class="w-full px-3 py-2 bg-gray-700 text-white rounded">{{ $song->description }}</textarea>
                                                    </div>

                                                    <!-- Genre -->
                                                    <div class="mb-4">
                                                        <label for="genre_id"
                                                            class="block text-white font-bold mb-2">Genre</label>
                                                        <select id="genre_id" name="genre_id"
                                                            class="w-full px-3 py-2 bg-gray-700 text-white rounded">
                                                            @foreach ($genres as $genre)
                                                                <option value="{{ $genre->id }}"
                                                                    {{ $song->genre_id == $genre->id ? 'selected' : '' }}>
                                                                    {{ $genre->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Buttons -->
                                                    <div class="flex justify-end">
                                                        <button type="button" @click="open = false"
                                                            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-2">
                                                            Cancel
                                                        </button>
                                                        <button type="submit"
                                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                                            Save Changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
