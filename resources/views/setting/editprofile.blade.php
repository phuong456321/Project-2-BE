@extends('setting.layoutsetting')

@section('title', 'Profile Information')

@section('content')
    <div class="container mx-auto p-4 bg-gray-900 text-gray-300 mt-10 max-w-3xl">
        <h1 class="text-4xl font-bold mb-6 text-white">Edit Profile</h1>
        <form class="space-y-6" action="{{ route('updateprofile') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-400">Avatar</label>
                <div class="flex flex-col items-center mt-4 space-y-4">
                    <img
                        id="avatar-preview"
                        src="{{ url('image/' . $user->avatar_id) }}"
                        alt="Avatar"
                        class="w-24 h-24 rounded-full object-cover border border-gray-700 shadow-lg">
                    <label
                        for="avatar"
                        class="cursor-pointer bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-all text-sm">
                        Upload Avatar
                    </label>
                    <input
                        type="file"
                        id="avatar" name="avatar"
                        class="hidden"
                        accept="image/*"
                        onchange="previewImage(event)">
                </div>
                <p id="upload-status" class="text-sm mt-2 text-gray-400"></p>
            </div>

            <!-- Editable Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-400">Username</label>
                <input
                    value="{{ $user->name }}"
                    type="text"
                    id="name" name="name"
                    class="w-full bg-gray-800 text-gray-300 border border-gray-700 rounded-md p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your username">
            </div>

            <!-- Editable Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-400">Biography</label>
                <textarea
                    id="bio" name="bio"
                    class="w-full bg-gray-800 text-gray-300 border border-gray-700 rounded-md p-2 mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Write your biography...">{{ $user->author ? $user->author->bio : '' }}</textarea>
            </div>

            <!-- Google Link Section -->
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-400 mb-2">Google Link</h3>
                @if ($user->google_id != null)
                <!-- If linked -->
                <div id="google-linked" class="flex items-center justify-between bg-green-500 p-4 rounded-md text-white">
                    <p class="text-sm">Account connected: <strong>{{ $user->google_id }}</strong></p>
                </div>
                @else
                <!-- If not linked -->
                <div id="google-not-linked">
                    <p class="text-sm text-gray-400">You haven't linked a Google account yet.</p>
                    <button
                        type="button"
                        onclick="window.location.href='{{ route('login-google') }}'"
                        class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">
                        Connect Google
                    </button>
                </div>
                @endif
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center mt-6">
                <a
                    href="{{ route('password.email') }}"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition-all">
                    Change Password
                </a>
                <button
                    type="submit"
                    class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        // Function to preview uploaded image
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('avatar-preview');
            const status = document.getElementById('upload-status');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result; // Set the preview image source
                    status.textContent = 'Avatar uploaded successfully!'; // Update upload status
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                status.textContent = 'No image selected.'; // Update if no image selected
            }
        }
    </script>
@endsection