@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/registration.js'); }}
    @endsection

    @section('content')


    <div class="row">
        <div class="col-md-7">
            <h2>Create an Account</h2>

            <?/* This is for the android phone / tab photo.. done through css? not sure */ ?>
            <div class="tab-content">
                <div class="tab-pane active" id="create"><br>

                {{ Form::open(['url' => 'registration', 'autocomplete' => 'off', 'role'=>'form', 'id'=>'new_user']) }}

                <div class="form-group row">
                    <div class="col-lg-12">
                        {{ Form::label("User Name:") }}
                        {{ Form::input("text", "username", null, ["class" => "form-control"]) }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        {{ Form::label("Email Address:") }}
                        {{ Form::input("text", "email", null, ["class" => "form-control"]) }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        {{ Form::label("Password:") }}
                        {{ Form::input("password", "password", null, ["class" => "form-control"]) }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        {{ Form::label("Password Confirmation:") }}
                        {{ Form::input("password", "password_confirmation", null, ["class" => "form-control"]) }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        {{ Form::label("Promo Code:") }}
                        {{ Form::input("text", "key", null, ["class" => "form-control"]) }}
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        {{ Form::button("Register", ["type" => "submit", "class" => "btn btn-primary btn-lg", "id" => "registration-submit"]) }}
                    </div>
                </div>


                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
    </div>
</div>
@endsection

