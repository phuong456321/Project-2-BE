<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Basic Information Form
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <h2 class="text-white text-2xl mb-6">
            Profile
        </h2>
        <div class="flex flex-col md:flex-row items-center md:items-start">
            <div class="flex flex-col items-center mb-6 md:mb-0 md:mr-6">
                <img alt="Profile image of a person with curly hair" class="w-24 h-24 rounded-full mb-2" height="100" src="https://storage.googleapis.com/a1aa/image/D4IODxu7NuqyN5MCdBlwL48tDWeTKWACE4uyhM2p7Dee9yrnA.jpg" width="100" />
                <button class="text-blue-500">
                    Replace image
                </button>
            </div>
            <div class="flex-grow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-400 mb-1">
                            First name
                        </label>
                        <input class="w-full p-2 bg-gray-700 text-white rounded border border-gray-600" type="text" value="LQ" />
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-1">
                            Last name
                        </label>
                        <input class="w-full p-2 bg-gray-700 text-white rounded border border-gray-600" type="text" value="DAT" />
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-1">
                            BIO
                        </label>
                        <input class="w-full p-2 bg-gray-700 text-white rounded border border-gray-600" type="text" value="DeV" />
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-1">
                           Email
                        </label>
                        <input class="w-full p-2 bg-gray-700 text-white rounded border border-gray-600" type="text" value="abc@gmail.com" />
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button class="bg-gray-700 text-white py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button class="bg-white text-gray-800 py-2 px-4 rounded">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>