@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/registration/index.js'); }}
    @endsection

    @section('black-bar-text')
        Create Your redhotMAYO Account:
    @endsection

    @section('content')
    
    {{ Form::open(['url' => 'registration', 'autocomplete' => 'off', 'role'=>'form', 'id'=>'new_user']) }}

    <div class="col-lg-4">
        <img src="assets/dropdown-login.jpg" style="position:absolute; margin-left: -5%">
        {{ Form::input("username", "username", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Username"]) }}
        {{ Form::input("username", "email", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Email"]) }}
        <img src="assets/dropdown-login.jpg" style="position:absolute; margin-left: -5%">
        {{ Form::input("password", "password", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Password"]) }}
        {{ Form::input("password", "password_confirmation", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Password Confirmation"]) }}
        {{ Form::input("username", "key", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Promo Code"]) }}<br>
        <br>
    {{ Form::button("Register", ["type" => "submit", "class" => "btn btn-primary btn-lg button2", "id" => "registration-submit"]) }}
    </div>

    <div class="col-md-4 pull-right app-image">
        <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
    </div>

    {{ Form::close() }}

@endsection

