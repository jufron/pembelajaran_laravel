<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>todo</title>
</head>
<body>
    <table>
        <tr>
            <th>Title</th>
            <th>Desckripsi</th>
            <th>Aksi</th>
        </tr>
        @foreach ($todos as $todo)
        <tr>
            <td>{{ $todo->title }}</td>
            <td>{{ $todo->description }}</td>
            <td>{{ $todo->user_id }}</td>
            <td>
                @can('update', $todo)
                <p>update</p>
                @else
                <p>no update</p>
                @endcan
                @can('delete', $todo)
                <p>delete</p>
                @else
                <p>no delete</p>
                @endcan
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>
