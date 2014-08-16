<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Red Hot Mayo</title>

    {{ stylesheet_link_tag() }}
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    @yield('css')
  </head>
<body>

    @include('partials.unauth-banner')


  <div class="container">
    <div id="warnings"></div>
    @yield('content')
  </div>
  <div class="footer">

  </div>


    {{ javascript_include_tag() }}
    @yield('javascript')
</body>
</html>
