<p>Hello,</p>
<p>We have received a request to reset your password. Click the link below to reset your password:</p>

<a href="{{ route('password.reset.form', ['token' => $token, 'email' => $email]) }}">Reset Password</a>

<p>If you did not request a password reset, please ignore this email.</p>
<p>Best regards,<br>Support Team</p>
