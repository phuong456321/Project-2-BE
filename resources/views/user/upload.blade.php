<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Nhạc</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .container i {
            position: relative;
            top: 28px;
            left: 17rem;
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
            max-width: 30%;
            position: relative;
            left: 30rem;
        }

        #btn-upload:hover {
            background-color: #3e8e41;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">
    <a href="{{ route('home') }}" id="home">
        <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo" width="150">
    </a>
    <div class="container mx-auto p-6">
        <div class="max-w-3xl mx-auto bg-gray-800 rounded-lg shadow-md p-6">
            <i class="fa-solid fa-upload text-2xl text-white"></i>
            <h2 class="text-center text-2xl font-bold mb-6">Upload</h2>
            <form action="{{ route('uploadSong') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="author" value="{{ Auth::user()->id }}">
                <input type="hidden" name="status" value="pending">
                <div class="mb-4">
                    <label for="songName" class="block text-sm font-medium mb-2">Tên bài hát</label>
                    <input type="text" id="songName" name="song_name"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập tên bài hát" required>
                </div>


                <div class="mb-4">
                    <label for="region" class="block text-sm font-medium mb-2">Khu vực</label>
                    <select id="region" name="area"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="" disabled selected>Chọn khu vực</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="genre" class="block text-sm font-medium mb-2">Thể loại</label>
                    <select id="genre" name="genre"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                        <option value="" disabled selected>Chọn thể loại</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="coverImage" class="block text-sm font-medium mb-2">Ảnh bìa</label>
                    <input type="file" id="coverImage" name="image"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        accept="image/*" required>
                </div>

                <div class="mb-4">
                    <label for="lyric" class="block text-sm font-medium mb-2">Lyric</label>
                    <textarea id="lyric" name="lyric"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="5" placeholder="Nhập lời bài hát"></textarea>
                </div>

                <div class="mb-4">
                    <label for="audioFile" class="block text-sm font-medium mb-2">File Audio</label>
                    <input type="file" id="audioFile" name="audio"
                        class="w-full p-3 bg-gray-700 text-white rounded-lg border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        accept="audio/*" required>
                </div>


                <button id="btn-upload" type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200">
                    Upload
                </button>
            </form>
        </div>
    </div>
</body>

</html>
