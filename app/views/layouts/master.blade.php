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
        <div class="col-lg-12">
          <div class="logo">
            <a href="http://redhotmayo.com">
              <img src="http://redhotmayo.com/wp-content/uploads/2013/11/logo_website_small_2.png" alt="redhotMAYO">
            </a>
          </div>
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