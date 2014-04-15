<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Red Hot Mayo</title>
        {{ stylesheet_link_tag() }}
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        @yield('css')
    </head>
<body>



    @if (Session::get('flash_message'))
        <div class="flash">
            {{ Session::get('flash_message') }}
        </div>
    @endif


    <div class="banner">
      <div class="container banner-image">
        <div class="row">
          <div class="col-lg-2">
            <div class="logo">
              <a href="http://redhotmayo.com">
                <img src="assets/logo.png" alt="redhotMAYO" height="120">
              </a>
            </div>
          </div>
          <div class="col-lg-4 nav-buttons">

          </div>
          <div class="col-lg-6">
            <p class="banner-words">
              Get a leg up on the competition with new restaurant openings,<br>
              delivered directly to your phone or tablet,<br>
              as soon as they come out!
            </p>
          </div>
        </div>
        <div class="row">
          <ul class="nav nav-pills pull-right">
            @if (isset($username))
              <li><a href="profile">{{$username}}</a></li>
            @endif
            <li><a href="logout">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container">
      @yield('content')
    </div>

    {{ javascript_include_tag() }}
    @yield('javascript')

</body>
</html>