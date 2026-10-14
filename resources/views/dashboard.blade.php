<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Hello there!</h1>
    
    <p>You Logged in using {{ Auth::user()->email }}</p>
    <br>

    <p><a href="{{ route('customer.index') }}" class="btn btn-primary">Manage Customers</a></p>
    <br>

    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-danger">Logout here</button>
    </form>
</body>
</html>