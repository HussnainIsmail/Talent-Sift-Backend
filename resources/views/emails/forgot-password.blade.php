<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
</head>
<body>
    <h1>Hello {{ $userName }},</h1>
    <p>You requested a password reset. Please use the following OTP to reset your password:</p>
    <h2>{{ $otp }}</h2>
    <p>This OTP will expire in 10 minutes.</p>
</body>
</html>
