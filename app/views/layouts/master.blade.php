<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Red Hot Mayo</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        @yield('css')
    </head>
<body>
    @if (Session::get('flash_message'))
        <div class="flash">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <div class="container">@yield('content')</div>
    <script src="../js/lib/jquery-1.11.0.min.js" type="application/javascript"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    @yield('javascript')
</body>
</html>