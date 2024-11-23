<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Verify Your Email Address</h1>
    <p>Thank you for registering! Please verify your email address by clicking the link below:</p>
    <a href="{{ route('verification.verify', $token) }}">Verify Email</a>
    <p>If you did not create an account, no further action is required.</p>
</body>
</html>
