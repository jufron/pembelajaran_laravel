<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>for loop</h1>

    @for ($i = 0; $i < count($data); $i++)
    <p>{{ $data[$i] }}</p>
    @endfor

    @foreach ($siswa as $s)
        <p>{{ $s }}</p>
    @endforeach

    @forelse ($siswa as $s)
        <p>{{ $s }}</p>
    @empty
        <p>tidak ada data</p>
    @endforelse

    @php
        $nama = 'indra';
        $email = 'indra@gmail.com';
    @endphp

    <h1>hello {{ $nama }} alamat email saya {{ $email }}</h1>
</body>
</html>
