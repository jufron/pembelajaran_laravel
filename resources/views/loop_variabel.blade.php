<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($data as $d)
        <p> ini iterasi ke : {{ $loop->iteration }} {{ $d }}</p>
    @endforeach

    @foreach ($data as $d)
        <p> ini index ke : {{ $loop->index }} {{ $d }}</p>
    @endforeach

    @foreach ($data as $d)
        <p>{{ $loop->remaining }}</p>
    @endforeach
</body>
</html>
