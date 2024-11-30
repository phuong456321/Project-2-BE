<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Upload Audio Files
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-gray-900 text-black font-sans">
    <a href="{{ route('home') }}" id="home">
        <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo" width="150">
    </a>
    <div class="max-w-4xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <i class="fa-solid fa-upload text-2xl text-white"></i>
                <h1 class="ml-2 text-xl font-semibold text-white">
                    Upload
                </h1>
            </div>
            <button class="text-2xl text-white">
                ×
            </button>
        </div>
        <div class="bg-gray-600 p-4 rounded-lg mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center ">
                    <i class="fa-solid fa-upload text-lg text-white"></i>
                    <span class="ml-2 text-sm text-white">
                        0% of uploads used
                    </span>
                </div>
                <span class="text-sm text-white">
                    0 of 180 minutes
                </span>
            </div>
        </div>
        <div class="text-center mb-6">
            <h2 class="text-2xl font-semibold text-white">
                Upload your audio files.
            </h2>
            <p class="text-sm text-gray-600 text-white">
                For best quality, use WAV, FLAC, AIFF, or ALAC. The maximum file size is 4GB uncompressed.
                <a class="text-blue-500" href="#">
                    Learn more.
                </a>
            </p>
        </div>
        <div class="border-dashed border-2 border-gray-300 p-8 rounded-lg text-center">
            <img alt="Upload icon" class="mx-auto mb-4" height="100" src="https://www.freeiconspng.com/uploads/upload-icon-30.png" width="100" />
            <p class="text-lg mb-4 text-white">
                Drag and drop audio files to get started.
            </p>
            <form method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <label class="text-white" for="file">Chọn file để upload:</label>
                <input class="text-white" type="file" name="file" id="file" required>
                <button class="text-black bg-white p-4 rounded-full hover:bg-gray-300 hover:scale-105 transition-transform duration-200" type="submit">Tải lên</button>
            </form>
        </div>
    </div>
</body>

</html>