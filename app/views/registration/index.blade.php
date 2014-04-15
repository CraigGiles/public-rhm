@extends('layouts.master')

@section('content')
<div class="page-header">
  <h1>Registration <small>Account Creation</small></h1>
</div>

@if($errors->has())
@foreach ($errors->all() as $error)
<div>{{ $error }}</div>
@endforeach
@endif
<div class="row">
  <div class="col-md-6">
    <ul class="nav nav-tabs" id="tabs">
      <li class="active"><a href="#create" data-toggle="tab">Create an Account</a></li>
      <li><a href="#signin" data-toggle="tab">Sign In</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="create">
        <br>
        {{ Form::open(['url' => 'registration', 'autocomplete' => 'off', 'role'=>'form', 'id'=>'new_user']) }}

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
            <label for="password_confirmation" class="control-label">Password Confirmation:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-validation-match-match="password">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-12">
            <label for="email" class="control-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-12">
            <label for="key" class="control-label">Promo Code:</label>
            <input type="key" name="key" id="key" class="form-control">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-lg-12">
            <button type="submit" class="btn btn-primary btn-lg pull-right">Register</button>
          </div>
        </div>

        {{ Form::close() }}
      </div>

      <div class="tab-pane" id="signin">
        <br>
        {{ Form::open(array('route' => 'sessions.store')) }}

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
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
  </div>
</div>

@endsection

@section('javascript')
<script>
  $(document).ready(function() {
    $('#tabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })

    $('#new_user').bootstrapValidator({
      fields: {
        username: {
          validators: {
            notEmpty: {
              message: 'This field is required and cannot be empty'
            }
          }
        },
        password: {
          validators: {
            notEmpty: {
              message: 'this field is required and cannot be empty'
            },
            identical: {
              field: 'password_confirmation',
              message: 'Passwords do not match'
            }
          }
        },
        password_confirmation: {
          validators: {
            notEmpty: {
              message: 'This field is required and cannot be empty'
            },
            identical: {
              field: 'password',
              message: "Passwords do not match"
            }
          }
        },
        email: {
          validators: {
            notEmpty: {
              message: 'The email address is required'
            },
            emailAddress: {
              message: 'The input is not a valid email address'
            }
          }
        },
        key: {
          validators: {
            notEmpty: {
              message: 'The promo code is required'
            }
          }
        }
      }
//      submitHandler: function(validator, form, submitButton) {
//        var formObject = {
//          username: $('[name=]'),
//          password:'',
//          password_confirmation:'',
//          email:'',
//          _token:'',
//          key:''
//        };
//
//        $.ajax({
//          url: 'registration',
//          type: 'POST',
//          dataType: 'json',
//          data: JSON.stringify(formObject),
//          cache: true,
//          complete: function(data) {
//            if (data.status === 401 || data.status === 200) {
//              if (data.responseJSON.redirect){
//                window.location.href = data.responseJSON.redirect;
//              }
//            } else {
//            }
//          }
//        });
//      }
    });
  });
</script>
@stop