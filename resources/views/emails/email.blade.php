<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold text-center mb-2">Email details</h1>
        <p class="text-center text-gray-600 mb-6">Enter your email to verify your profile information.</p>
        <form method="POST" action="#">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Your Email..." required>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white p-3 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Continue</button>
        </form>
    </div>
</body>

</html>