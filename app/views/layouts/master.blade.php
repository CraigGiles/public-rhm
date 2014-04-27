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



<!--  @if (Session::get('flash_message'))-->
<!--    <div class="flash">-->
<!--      {{ Session::get('flash_message') }}-->
<!--    </div>-->
<!--  @endif-->


  <div class="banner">
    <div class="container banner-image">
      <div class="row">
        <div class="col-sm-12">
          <div class="logo">
          </div>
        </div>
      </div>
      <div class="row">
        <ul class="nav nav-pills pull-right">
          @if (isset($username))
            <li><a href="profile">{{$username}}</a></li>
            <li><a href="logout">Logout</a></li>
          @endif

        </ul>
      </div>
    </div>
  </div>
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