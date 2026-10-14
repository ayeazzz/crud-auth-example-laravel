<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Login</h1>
    <hr>
    @if (session('success'))
        <p class="alert-success">{{ session('success') }}</p>
    @endif
    <form action="{{ route('login.store') }}" method="post" class="card">
        @csrf 
        <p>Email <input type="text" name="email" value="{{ old('email') }}"></p>
        <p>Password <input type="password" name="password"></p>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Login</button>
        <p>
            Don't have an account yet? <a href="{{ route('register') }}">Click here</a> to register
        </p>
    </form>
</body>
</html>