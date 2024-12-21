<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo-home.webp') }}" type="image/webp">
    <title>Email Verification</title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>


<body class="bg-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg w-full text-center">
        <h1 class="text-2xl font-semibold mb-2">
            Verify your email
        </h1>
        <p class="text-gray-600 mb-6">
            You will need to verify your email to complete registration
        </p>
        <img alt="Illustration of a mailbox with an email inside" class="mx-auto mb-6" height="200" src="https://storage.googleapis.com/a1aa/image/4RPBfxT0W3yhYSQXS1qyN9NER8B0ZatuWUigqG3QZcqgBS6JA.jpg" width="200" />
        <p class="text-gray-600 mb-6">
            Thank you for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn’t receive the email, we will gladly send you another.
        </p>
        <div class="flex justify-center space-x-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="bg-blue-600 text-white px-4 py-2 rounded bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Resend Email
                </button>
            </form>
        </div>
    </div>
</body>

</html>