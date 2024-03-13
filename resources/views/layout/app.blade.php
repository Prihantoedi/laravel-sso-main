<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token() }}">
    <title>{{$data['app_name']}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
        integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
        crossorigin="anonymous" />
    
    <style>
        .captcha-container{
            width: 20rem;
            background: rgb(190, 190, 190);
            border-radius: 10px;
            padding: 2rem;
            display: flex;
            align-content: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            margin: auto;
        }

        #captcha {
            border-radius: 5px;
            border: 1px solid gainsboro;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        input[type="text"]{
            padding: 12px 20px;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            width: 100%;
            margin: 12px 0;
        }

        .regenerateCaptchaBtn{
            background-color: #2f66f5;
            border: none;
            color: white;
            padding: 12px 12px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
            width: 30%;
            border-radius: 5px;
        }

        #captcha-notif{
            display: none;
        }

        canvas{
            pointer-events: none;
        }

    </style>
</head>

@if(isset($auth_data))
    <body onload="createCaptcha()">
        @yield('content')
    </body>

@else
    <body>
        @yield('content')
    </body>

@endif


</html>