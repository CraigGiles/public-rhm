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

    <div class="container">
      <div class="row">
        <div class="col-lg-2">
          <div class="logo">
            <a href="http://redhotmayo.com">
              <img src="http://redhotmayo.com/wp-content/uploads/2013/11/logo_website_small_2.png" alt="redhotMAYO">
            </a>
          </div>
        </div>
        <div class="col-lg-10 nav-buttons">
          <ul class="list-inline pull-right">
            <?php
              try {
                isset($username);
              } catch(Exception $e){
                $username = null;
              }
            ?>
            @if (isset($username))
              <li><a href="profile">{{$username}}</a></li>
            @endif
            <li><a href="logout">Logout</a></li>
          </ul>
        </div>
      </div>
      <div class="row shadow-wrapper">
        <div class="shadow-top col-lg-12"></div>
      </div>

      @yield('content')

    </div>

    {{ javascript_include_tag() }}
    @yield('javascript')

</body>
</html>