<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Customer</title>
</head>
<body>
    <form action="{{ route('customer.update',$customer->id) }}" method="post">
        @csrf
        <p>First Name : <input type="text" name="first_name" value="{{ $customer->first_name }}"></p>
        <p>Last Name : <input type="text" name="last_name" value="{{ $customer->last_name }}"></p>
        <button type="submit">Save</button>
    </form>
</body>
</html>