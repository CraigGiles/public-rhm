<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Red Hot Mayo</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    </head>
<body>
    @if (Session::get('flash_message'))
        <div class="flash">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <div class="container">@yield('content')</div>
</body>
</html>