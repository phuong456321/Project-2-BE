<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo-home.webp') }}" type="image/webp">
    <title>Upload Nhạc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .container i {
            font-size: 33px;
            color: #fff;
        }

        #btn-upload {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 50px;
            transition: background-color 0.3s;
        }

        #btn-upload:hover {
            background-color: #3e8e41;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">
    <div class="flex flex-col items-center py-6">
        <a href="{{ route('home') }}" id="home" class="mb-6">
            <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo" width="150">
        </a>
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex justify-center mb-6">
                    <i class="fa-solid fa-upload text-3xl"></i>
                </div>
                <h2 class="text-center text-2xl font-bold mb-6">Upload</h2>
                <form action="{{ route('uploadSong') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="author" value="{{ Auth::user()->author_id }}">
                    <input type="hidden" name="status" value="pending">

                    <div class="mb-4">
                        <label for="songName" class="block text-sm font-medium mb-2">Song Name</label>
                        <input type="text" id="songName" name="song_name" class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter a song name" required>
                    </div>

                    <div class="mb-4">
                        <label for="region" class="block text-sm font-medium mb-2">Area</label>
                        <select id="region" name="area" class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled selected>Select a region</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="genre" class="block text-sm font-medium mb-2">Genre</label>
                        <select id="genre" name="genre" class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled selected>Select a genre</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="coverImage" class="block text-sm font-medium mb-2">Cover photo</label>
                        <input type="file" id="coverImage" name="image" class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" required>
                    </div>

                    <div class="mb-4">
                        <label for="lyric" class="block text-sm font-medium mb-2">Lyric</label>
                        <textarea id="lyric" name="lyric" class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" placeholder="Enter lyrics"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="audioFile" class="block text-sm font-medium mb-2">File Audio</label>
                        <input type="file" id="audioFile" name="audio" class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="audio/*" required>
                    </div>

                    <div class="mb-4">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms" class="ml-2 text-sm">
                            I agree to the
                            <a href="/musicservice" target="_blank" class="text-blue-500 hover:text-blue-700 underline">terms and conditions</a>.
                        </label>
                    </div>

                    <div class="flex justify-center">
                        <button id="btn-upload" type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
