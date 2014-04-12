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

    <div class="header-wrapper">
      <div class="header-v1">
        <header id="header">
          <div class="avada-row" style="margin-top:0px;margin-bottom:0px;">
            <div class="logo" style="margin-right:0px;margin-top:0;margin-left:0px;margin-bottom:5px;">
              <a href="http://redhotmayo.com">
                <img src="http://redhotmayo.com/wp-content/uploads/2013/11/logo_website_small_2.png" alt="redhotMAYO" class="normal_logo">
              </a>
            </div>
            <nav id="nav" class="nav-holder">
              <ul id="nav" class="menu">
                <li id="menu-item-29" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-7 current_page_item menu-item-29"><a title="Home" href="http://redhotmayo.com/">Home</a></li>
                <li id="menu-item-33" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-33"><a href="http://redhotmayo.com/our-story/">Our Story</a></li>
                <li id="menu-item-38" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-38"><a href="http://redhotmayo.com/services/">Services</a></li>
                <li id="menu-item-43" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-43"><a href="http://redhotmayo.com/pricing/">Pricing</a></li>
                <li id="menu-item-46" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-46"><a href="http://redhotmayo.com/contact-us/">Contact Us</a></li>
              </ul>
              <div id="undefined" class="dd-container" style="width: 100%;">
                <div class="dd-select" style="width: 100%; background-color: rgb(238, 238, 238); background-position: initial initial; background-repeat: initial initial;">
                  <input class="dd-selected-value" type="hidden" value="">
                  <a class="dd-selected">
                    <label class="dd-selected-text">Go to...</label>
                  </a>
                  <span class="dd-pointer dd-pointer-down"></span>
                </div>
                <ul class="dd-options dd-click-off-close" style="width: 100%;">
                  <li><a class="dd-option dd-option-selected"> <label class="dd-option-text">Go to...</label></a></li>
                  <li><a class="dd-option"> <input class="dd-option-value" type="hidden" value="http://redhotmayo.com/"> <label class="dd-option-text">Home</label></a></li>
                  <li><a class="dd-option"> <input class="dd-option-value" type="hidden" value="http://redhotmayo.com/our-story/"> <label class="dd-option-text">Our Story</label></a></li>
                  <li><a class="dd-option"> <input class="dd-option-value" type="hidden" value="http://redhotmayo.com/services/"> <label class="dd-option-text">Services</label></a></li>
                  <li><a class="dd-option"> <input class="dd-option-value" type="hidden" value="http://redhotmayo.com/pricing/"> <label class="dd-option-text">Pricing</label></a></li>
                  <li><a class="dd-option"> <input class="dd-option-value" type="hidden" value="http://redhotmayo.com/contact-us/"> <label class="dd-option-text">Contact Us</label></a></li>
                </ul>
              </div>
            </nav>
          </div>
        </header>
      </div>
    </div>

    <div id="layerslider-container">
      <div id="layerslider-wrapper">
        <div class="ls-shadow-top"></div>
      </div>
    </div>

    @if (Session::get('flash_message'))
        <div class="flash">
            {{ Session::get('flash_message') }}
        </div>
    @endif

    <div class="container" style="margin-top: 34px;">@yield('content')</div>
    {{ javascript_include_tag() }}
    @yield('javascript')
</body>
</html>