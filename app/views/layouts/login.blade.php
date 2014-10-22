{{ Form::open(array('route' => 'sessions.store', 'id' => 'signin_form')) }}

<div class="main-left form-group row">
    <img src="assets/dropdown-login.jpg" style="position:absolute;">

    <div class="col-lg-12">
        {{ Form::input("username", "username", null, ["style" => "position:relative;", "placeholder" => "Username", "class" => "form-control login-username"]) }}
    </div>

    <div class="col-lg-12">
        {{ Form::input("password", "password", null, ["style" => "position:relative;", "class" => "form-control", "placeholder" => "Password"]) }}
    </div>

    <div class="col-lg-12" style="padding-top:15px">
        {{ Form::button("Login", ["type" => "submit", "class" => "btn btn-primary btn-lg button2"]) }}
    </div>

    <font style="color: #ffffff; position: relative; font-family:robotoregular;">
        {{ link_to_route('password_resets.create', 'Forgot your password?', null, ["class" => "btn"]) }}
    </font>
    
</div>

{{ Form::close() }}

<p>
<a href="registration">{{ HTML::image('assets/setup-account.png') }}</a>

