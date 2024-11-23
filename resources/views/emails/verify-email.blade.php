<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification Required</h1>
    <p>Thank you for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn’t receive the email, we will gladly send you another.</p>

    <!-- Gửi lại email xác thực -->
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>
</body>
</html>
