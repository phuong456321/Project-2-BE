<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-center text-xl font-semibold mb-6">Password change</h2>
        <form method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password">New Password:</label>
                <input class="w-full px-3 py-2 border rounded" type="password" id="password" value="password">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="confirm-password">Confirm New Password:</label>
                <input class="w-full px-3 py-2 border rounded" type="password" id="confirm-password" value="confirm password">
            </div>
            <div class="text-center">
                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
            </div>
        </form>
    </div>
</body>

</html>