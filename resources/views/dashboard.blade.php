<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Hello there!</h1>
    
    <p>You Logged in using {{ Auth::user()->email }}</p>
    <br>

    <form action="{{ route('auth.logout') }}" method="post">
        @csrf
        <button type="submit">Logout here</button>
    </form>
</body>
</html>