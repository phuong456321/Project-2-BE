@extends('admin.admin')
@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
    .hidden {
        display: none;
    }

    #flash-message {
        transition: opacity 0.5s ease-in-out;
        opacity: 1;
    }

    #flash-message.fade-out {
        opacity: 0;
    }
</style>
@endsection
@section('content')
    <h1 class="text-2xl font-bold mb-4">Manage Authors</h1>
    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-white">Author List</h2>
            <!-- Button Create Author -->
            <button id="create-author-btn" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                + Create Author
            </button>
        </div>

        <!-- Filter & Search Section -->
        <div class="flex flex-wrap gap-4 mb-4 items-center">
            <!-- Filter khu vực -->
            <div class="flex flex-col">
                <label for="area" class="text-white font-medium mb-1">Filter by Area</label>
                <select id="area" class="bg-gray-700 text-white py-2 px-4 rounded-md">
                    <option value="">All Areas</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Box -->
            <div class="flex flex-col flex-1">
                <label for="search" class="text-white font-medium mb-1">Search Author</label>
                <input id="search" type="text" placeholder="Enter author name..."
                    class="bg-gray-700 text-white py-2 px-4 rounded-md">
            </div>
        </div>
        <!-- Table for users -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto bg-gray-800">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="px-4 py-2">Image</th>
                        <th class="px-4 py-2">Author Name</th>
                        <th class="px-4 py-2">Area</th>
                        <th class="px-4 py-2">Number of Songs</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="authors-list">
                    <!-- Content will be loaded here -->
                </tbody>
            </table>
        </div>
        <div id="loading" style="display:none;">
            Loading more authors...
        </div>
    </div>
    <!-- Popup Create Author -->
    <div id="create-author-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-2xl font-bold text-white mb-4">Create New Author</h3>
            <form id="create-author-form" enctype="multipart/form-data">
                @csrf
                <!-- Name -->
                <div class="mb-4">
                    <label for="author-name" class="block text-white mb-2">Author Name</label>
                    <input id="author-name" type="text" name="name" required
                        class="w-full bg-gray-700 text-white py-2 px-4 rounded-md">
                </div>
                <!-- Description -->
                <div class="mb-4">
                    <label for="author-bio" class="block text-white mb-2">Bio</label>
                    <input id="author-bio" type="text" name="bio"
                        class="w-full bg-gray-700 text-white py-2 px-4 rounded-md">
                </div>
                <!-- Area -->
                <div class="mb-4">
                    <label for="author-area" class="block text-white mb-2">Area</label>
                    <select id="author-area" name="area" class="w-full bg-gray-700 text-white py-2 px-4 rounded-md">
                        <option value="">Select Area</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Image -->
                <div class="mb-4">
                    <label for="author-image" class="block text-white mb-2">Image</label>
                    <input id="author-image" type="file" name="image" accept="image/*"
                        class="w-full bg-gray-700 text-white py-2 px-4 rounded-md">
                </div>
                <!-- Submit -->
                <div class="flex justify-end gap-4">
                    <button type="button" id="close-modal"
                        class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700">Cancel</button>
                    <button type="submit"
                        class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div id="flash-message" class="hidden fixed top-4 right-4 bg-blue-500 text-white py-2 px-4 rounded-lg shadow-lg z-50">
    </div>
@endsection
@push('scripts')
@vite('resources/js/admin/authors.js')
@endpush
