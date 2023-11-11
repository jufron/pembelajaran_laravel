<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .active {
            background-color: blue;
            color: white;
        }

        .text {
            font-family: sans-serif;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <a @class([
        'text',
        'active' => $isActive
        ]) href="#">Home</a>
</body>
</html>
