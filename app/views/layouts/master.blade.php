<!doctype html>
<html lang="en" xml:lang="en" xmlns= "http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title>redhotMAYO</title>

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,300,700" />
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />

        {{ stylesheet_link_tag() }}

        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

        
        @yield('css')

        @include('partials.mixpanel')
    </head>
    <body>
        @include('partials.unauth-banner')

        <div id="nav">
            <div style="padding-left: 8%; margin-top: -6px;">
                @yield('black-bar-text')
            </div>
        </div>

        <div class="container main-content">
            <div id="warnings">
            </div>

            @yield('content')
        </div>


        <div class="footer">
        </div>


        {{ javascript_include_tag() }}
        @yield('javascript')
    </body>
</html>
