<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <p><a href="{{ route('customer.index') }}">Back to Customer List</a></p>
    <form action="{{ route('customer.update',$customer->id) }}" method="post" class="card">
        @csrf
        <p>First Name : <input type="text" name="first_name" value="{{ $customer->first_name }}"></p>
        <p>Last Name : <input type="text" name="last_name" value="{{ $customer->last_name }}"></p>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</body>
</html>