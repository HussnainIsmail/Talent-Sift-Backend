<!DOCTYPE html>
<html>

<head>
    <title>Email Confirmation</title>
</head>

<body>
    <h1> {{ $user->name }}!</h1>
    <p>Some One Recentally reset your password on this{{ $user->email }}. if it is your plz confirm it</p>
    <a href="">Reset PAssword</a>
    <p>If it is not you than we request you to update your password to </p>
</body>

</html>