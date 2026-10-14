<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Register</h1>
    <hr>

    <form action="{{ route('register.store') }}" method="post" class="card">
        @csrf 
        <p>Name <input type="text" name="name" value="{{ old('name') }}"></p>
        <p>Email <input type="text" name="email" value="{{ old('email') }}"></p>
        <p>Password <input type="password" name="password"></p>
        <p>Confirm Password <input type="password" name="password_confirmation"></p> @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <button type="submit" class="btn btn-primary">Register</button>
        <p>
            Already have an account? <a href="{{ route('login') }}">Click here</a> to login
        </p>
    </form>
</body>
</html>