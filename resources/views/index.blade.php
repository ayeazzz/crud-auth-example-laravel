<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD Customer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <p><a href="{{ route('dashboard.index') }}">Dashboard</a></p>
    <a href="{{ route('customer.create') }}" class="btn btn-primary">Add New Customer</a> <table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th colspan="2">Action</th> </tr>
    </thead>
    <tbody>
        @foreach ($customers as $row)
        <tr>
            <td>{{ $row->first_name }}</td>
            <td>{{ $row->last_name }}</td>
            <td>
                <a href="{{ route('customer.edit', $row->id) }}" class="btn btn-warning">Edit</a>
            </td>
            <td>
                <a href="{{ route('customer.delete', $row->id) }}" class="btn btn-danger">Delete</a>
            </td>
        </tr>
            
        @endforeach
    </tbody>
</table>
</body>
</html>