@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/registration/index.js'); }}
    @endsection

    @section('black-bar-text')
        Create Your redhotMAYO Account:
    @endsection

    @section('content')
    @include("partials.errors")
    
    {{ Form::open(['url' => 'registration', 'autocomplete' => 'off', 'role'=>'form', 'id'=>'new_user']) }}

    <div class="col-sm-4">
        <img src="assets/dropdown-login.jpg" style="position:absolute; margin-left: -5%">
        {{ Form::input("username", "username", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Username *"]) }}
        {{ Form::input("username", "email", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Email *"]) }}
        <img src="assets/dropdown-login.jpg" style="position:absolute; margin-left: -5%">
        {{ Form::input("password", "password", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Password *"]) }}
        {{ Form::input("password", "password_confirmation", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Password Confirmation *"]) }}
        {{ Form::input("username", "key", null, ["class" => "form-control", "style" => "position:relative;", "placeholder" => "Promo Code"]) }}<br>
        <br>
        {{ Form::button("Register", ["type" => "submit", "class" => "btn btn-primary btn-lg button2", "id" => "registration-submit"]) }}
        <br>
        <font style="position: relative; font-family:roboto; font-weight: 700; white-space:nowrap; padding-bottom: 20px">
            * Important! Remember your password as you will need it to log into your redhotMAYO app.
        </font>    
    </div>


    <div class="col-md-4 pull-right app-image">
        <img src="assets/tablets.png"/>
    </div>
    

    {{ Form::close() }}

@endsection

