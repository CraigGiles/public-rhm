{{ Form::open(array('route' => 'sessions.store', 'id' => 'signin')) }}

<div class="form-group row">
  <div class="col-lg-12">
    <label for="username" class="control-label">User Name:</label>
    <input type="text" name="username" id="username" class="form-control">
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-12">
    <label for="password" class="control-label">Password:</label>
    <input type="password" name="password" id="password" class="form-control">
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-12">
    <button type="submit" class="btn btn-primary btn-lg pull-right">Login</button>
  </div>
</div>
<!--      {{ link_to_route('password_resets.create', 'Forgot your password') }}-->
{{ Form::close() }}