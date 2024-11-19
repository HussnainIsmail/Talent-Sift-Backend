<!DOCTYPE html>
<html>
<head>
    <title>Registration Confirmation</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thank you for registering with us. Your email address is {{ $user->email }}.</p>
    <p>We are excited to have you as a part of our community.</p>
</body>
</html>
