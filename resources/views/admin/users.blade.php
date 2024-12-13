@extends('admin.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Manage Users</h1>
    <div class="bg-gray-800 p-4 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-white mb-4">User List</h2>
        <!-- Filter & Search Section -->
        <div class="flex flex-wrap gap-4 mb-4 items-center">
            <!-- Filter khu vực -->
            <div class="flex flex-col">
                <label for="status" class="text-white font-medium mb-1">Filter by Status</label>
                <select id="status" class="bg-gray-700 text-white py-2 px-4 rounded-md">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Search Box -->
            <div class="flex flex-col flex-1">
                <label for="search" class="text-white font-medium mb-1">Search User</label>
                <input id="search" type="text" placeholder="Enter user name..."
                    class="bg-gray-700 text-white py-2 px-4 rounded-md">
            </div>
        </div>
        <!-- Table for users -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto bg-gray-800">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="px-4 py-2">Avatar</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Plan</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Verify</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Restricted Tracks</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="users-list">
                    <!-- Content will be loaded here -->
                </tbody>
            </table>
        </div>
        <div id="loading" style="display:none;">
            Loading more users...
        </div>
    </div>
    <div id="flash-message" class="hidden fixed top-4 right-4 bg-blue-500 text-white py-2 px-4 rounded-lg shadow-lg z-50">
    </div>

    <!-- Modal xác nhận -->
    <div id="confirmation-modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-black">
            <h2 id="modal-title" class="text-xl font-semibold text-center mb-4">Confirm Ban User</h2>
            <p id="modal-message" class="text-center mb-6">Are you sure you want to ban this user?</p>
            <div class="flex justify-between">
                <button id="confirm-ban" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition">Yes,
                    Banned</button>
                <button id="cancel-ban"
                    class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400 transition">Cancel</button>
            </div>
        </div>
    </div>
@endsection


@vite('resources/js/admin/user.js')
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
