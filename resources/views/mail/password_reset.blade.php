<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Performance Analyzer</title>
</head>
<body>
    <div>
        <h3><b>Hi..</b> {{ $data->name }}</h3>
        <p><br><br>
            Please reset your password via the below link...
        </p>
        <a href="{{ url('reset/new-password/'.$data->remember_token) }}">
            {{ $data->remember_token }}
        </a>
    </div>
</body>
</html>
