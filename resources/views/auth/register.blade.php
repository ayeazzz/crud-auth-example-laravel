<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <hr>

    <form action="{{ route('auth.register') }}" method="post">
        @csrf 
        <p>Email <input type="text" name="email" value="{{ old('email') }}"></p>
        <p>Password <input type="password" name="password"></p>
        <p>Confirm Password <input type="password" name="password_confirmation"></p> @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <button type="submit">Register</button>
        <p>
            Already have an account? <a href="{{ route('auth.login') }}">Click here</a> to login
        </p>
    </form>
</body>
</html>