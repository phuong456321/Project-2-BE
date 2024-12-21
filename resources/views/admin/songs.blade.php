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
            <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }" x-init="open = {{ $errors->any() ? 'true' : 'false' }}" class="self-end w-full sm:w-auto">
                <!-- Button to open the popup -->
                <button @click="open = true"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 whitespace-nowrap w-full sm:w-auto">
                    + Create New Song
                </button>

                <!-- Popup -->
                <div x-show="open" @keydown.escape.window="open = false"
                    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                    <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg max-h-[80vh] overflow-y-auto scrollbar-none">
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-white">Create New Song</h2>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-200">✖</button>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('admin.createSong') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="status" class="hidden" value="pending">
                            <div class="mb-4">
                                <label for="song_name" class="block text-white font-bold mb-2">Song Name</label>
                                <input type="text" id="song_name" name="song_name"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('song_name') is-invalid @enderror" value="{{ old('song_name') }}">
                                @error('song_name')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="author_id" class="block text-white font-bold mb-2">Author</label>
                                <select id="author_id" name="author"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('author') is-invalid @enderror">
                                    <option value="">Select Author</option>
                                    @foreach ($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->author_name }}</option>
                                    @endforeach
                                </select>
                                @error('author')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="genre" class="block text-white font-bold mb-2">Genre</label>
                                <select id="genre" name="genre"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('genre') is-invalid @enderror">
                                    <option value="">Select Genre</option>
                                    @foreach ($genres as $genre)
                                        <option value="{{ $genre->id }}" {{ old('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                                @error('genre')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="area" class="block text-white font-bold mb-2">Area</label>
                                <select id="area" name="area"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('area') is-invalid @enderror">
                                    <option value="">Select Area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}" {{ old('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="image" class="block text-white font-bold mb-2">Song Image</label>
                                <input type="file" id="image" name="image"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('image') is-invalid @enderror" accept=".jpeg, .png, .jpg" value="{{ old('image') }}">
                                @error('image')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="audio" class="block text-white font-bold mb-2">Song Audio</label>
                                <input type="file" id="audio" name="audio"
                                    class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('audio') is-invalid @enderror" accept=".mp3, .wav, .ogg" value="{{ old('audio') }}">
                                @error('audio')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="lyric" class="block text-white font-bold mb-2">Song Lyrics</label>
                                <textarea id="lyric" name="lyric" class="w-full px-3 py-2 bg-gray-700 text-white rounded @error('lyric') is-invalid @enderror">{{ old('lyric') }}</textarea>
                                @error('lyric')
                                    <p class="invalid-feedback text-red-500">*{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="button" @click="open = false"
                                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-2">
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
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Author</th>
                        <th class="px-4 py-2">Area</th>
                        <th class="px-4 py-2">Genre</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
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
                                    <img src="/image/{{ $song->img_id }}" alt="Song Image"
                                        class="w-10 h-10 rounded-full" loading="lazy">
                                </td>
                                <td class="px-4 py-2">{{ $song->song_name }}</td>
                                <td class="px-4 py-2">{{ $song->author ? $song->author->author_name : 'Unknown' }}</td>
                                <td class="px-4 py-2 text-center">{{ $song->area ? $song->area->name : 'Unknown' }}</td>
                                <td class="px-4 py-2 text-center">{{ $song->genre ? $song->genre->name : 'Unknown' }}</td>
                                <td class="px-4 py-2 text-center">
                                    <form action="{{ route('admin.updateStatus', $song->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="rounded px-2 py-1 
                                        @if ($song->status == 'published') bg-green-600 text-white 
                                        @elseif ($song->status == 'deleted') bg-red-600 text-white 
                                        @elseif ($song->status == 'inactive') bg-yellow-600 text-black 
                                        @elseif ($song->status == 'pending') bg-gray-600 text-white 
                                        @else bg-gray-800 text-white @endif">
                                            <option value="published"
                                                {{ $song->status == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="deleted" {{ $song->status == 'deleted' ? 'selected' : '' }}>
                                                Deleted</option>
                                            <option value="inactive" {{ $song->status == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                            <option value="pending" {{ $song->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-2 text-center">
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
                                                    <button @click="open = false"
                                                        class="text-gray-400 hover:text-gray-200">
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
                                                        <label for="song_name"
                                                            class="block text-white font-bold mb-2">Song Name</label>
                                                        <input type="text" id="song_name" name="song_name"
                                                            value="{{ $song->song_name }}"
                                                            class="w-full px-3 py-2 bg-gray-700 text-white rounded"
                                                            required>
                                                    </div>

                                                    <!-- Author -->
                                                    <div class="mb-4">
                                                        <label for="author_id"
                                                            class="block text-white font-bold mb-2">Author</label>
                                                        <select id="author_id" name="author_id"
                                                            class="w-full px-3 py-2 bg-gray-700 text-white rounded">
                                                            @foreach ($authors as $author)
                                                                <option value="{{ $author->id }}"
                                                                    {{ $song->author_id == $author->id ? 'selected' : '' }}>
                                                                    {{ $author->author_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Area -->
                                                    <div class="mb-4">
                                                        <label for="area_id"
                                                            class="block text-white font-bold mb-2">Area</label>
                                                        <select id="area_id" name="area_id"
                                                            class="w-full px-3 py-2 bg-gray-700 text-white rounded">
                                                            @foreach ($areas as $area)
                                                                <option value="{{ $area->id }}"
                                                                    {{ $song->area_id == $area->id ? 'selected' : '' }}>
                                                                    {{ $area->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
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

                                                    <!-- Lyric -->
                                                    <div class="mb-4">
                                                        <label for="lyric"
                                                            class="block text-white font-bold mb-2">Lyric</label>
                                                        <textarea id="lyric" name="lyric" class="w-full px-3 py-2 bg-gray-700 text-white rounded">{{ $song->lyric }}</textarea>
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

                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = true"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                            Async Lyric
                                        </button>

                                        <!-- Popup -->
                                        <div x-show="open"
                                            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                                            <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                                                <!-- Header -->
                                                <div class="flex justify-between items-center mb-4">
                                                    <h2 class="text-xl font-bold text-white">Async Lyric Song</h2>
                                                    <button @click="open = false"
                                                        class="text-gray-400 hover:text-gray-200">
                                                        ✖
                                                    </button>
                                                </div>

                                                <!-- Form -->
                                                <form action="{{ route('admin.asyncLyrics') }}" method="POST">
                                                    @csrf
                                                    <input type="text" name="song_id" value="{{ $song->id }}" hidden>

                                                    <!-- Lyric -->
                                                    <div class="mb-4">
                                                        <label for="lyric"
                                                            class="block text-white font-bold mb-2">Lyric</label>
                                                        <textarea id="lyric" name="lyrics" class="w-full px-3 py-2 bg-gray-700 text-white rounded"></textarea>
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
