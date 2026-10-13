<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <hr>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    <form action="{{ route('auth.login.store') }}" method="post">
        @csrf 
        <p>Email <input type="text" name="email" value="{{ old('email') }}"></p>
        <p>Password <input type="password" name="password"></p>

        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit">Login</button>
        <p>
            Don't have an account yet? <a href="{{ route('auth.register') }}">Click here</a> to register
        </p>
    </form>
</body>
</html>