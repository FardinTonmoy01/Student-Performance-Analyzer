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
        <p>
            <b>Hi..</b> {{ $data->name }}, <span style="color: green;">Congratulations! Your Registration has been Successfull.</span>
            <br>
            <div>
                <h3 style="color: green;">Login Credential</h3>
                <span>Username: <b>{{ $data->email }}</b></span><br>
                <span>Password: <b>123456</b></span>
                <br>
                <span style="color: red;">[Note: The given password is temporary generated. Please change password after login to secure your account.]</span>
            </div>
            <br>
        </p>
        <div>
            To <span style="color:red">Sign In</span> <b>Student Performance Analyzer</b> please verify your account via below link...
            <a href="{{ route('mail.verify', $data->email_verified_at) }}">
                {{ route('mail.verify', $data->email_verified_at) }}
            </a>
        </div>
    </div>
</body>
</html>
