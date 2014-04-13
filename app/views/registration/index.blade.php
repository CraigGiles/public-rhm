@extends('layouts.master')

@section('content')
<div class="page-header">
  <h1>Registration <small>Account Creation</small></h1>
  <ol class="breadcrumb">
    <li class="text-muted">Region Selection</li>
    <li class="active" style="color: #333333;">Account Creation</a></li>
    <li class="text-muted">Confirmation</a></li>
  </ol>
</div>

@if($errors->has())
@foreach ($errors->all() as $error)
<div>{{ $error }}</div>
@endforeach
@endif

{{ Form::open(['url' => 'registration', 'autocomplete' => 'off', 'role'=>'form', 'id'=>'form']) }}

  <div class="form-group row">
    <div class="col-md-12 col-lg-6">
      <label for="username" class="control-label">User Name:</label>
      <input type="text" name="username" id="username" class="form-control">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-lg-6">
      <label for="password" class="control-label">Password:</label>
      <input type="password" name="password" id="password" class="form-control">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-lg-6">
      <label for="password_confirmation" class="control-label">Password Confirmation:</label>
      <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" data-validation-match-match="password">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-lg-6">
      <label for="email" class="control-label">Email:</label>
      <input type="email" name="email" id="email" class="form-control">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-12 col-lg-6">
      <button type="submit" class="btn btn-primary btn-lg pull-right">Register</button>
    </div>
  </div>
{{ Form::close() }}
@endsection

@section('javascript')
<script>
  $(document).ready(function() {
    $('#form').bootstrapValidator({
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
      },
      submitHandler: function(validator, form, submitButton) {

      }
    });
  });
</script>
@stop