{{ Form::open(array('route' => 'sessions.store', 'id' => 'signin_form')) }}

<div class="form-group row">
  <div class="col-lg-12">
    {{ Form::input("text", "username", null, ["class" => "form-control", "placeholder" => "User Name"]) }}
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-12">
    {{ Form::input("password", "password", null, ["class" => "form-control", "placeholder" => "Password"]) }}
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-12">
    {{ Form::button("Log in to redhotMAYO", ["type" => "submit", "class" => "btn btn-primary btn-lg"]) }}
    {{ link_to_route('password_resets.create', 'Forgot your password', null, ["class" => "btn pull-right"]) }}
  </div>
</div>


{{ Form::close() }}