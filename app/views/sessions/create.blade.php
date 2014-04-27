@extends('layouts.master')

@section('content')

<div class="page-header">
  <h1>
    Sign In
  </h1>
</div>

<div class="row">

  <div class="col-lg-7">
    @include('layouts.login')
  </div>

  <div class="col-md-5">
    <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
  </div>

</div>

@endsection


@section('javascript')

<script>
  $(document).ready(function() {
    /**
     * Signin Form validation
     */
    $('#signin_form').bootstrapValidator({
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
              message: 'This field is required and cannot be empty'
            }
          }
        }
      },
      submitHandler: function(validator, form, submitButton) {

        $.ajax({
          url: $('#signin_form').attr('action'),
          type: $('#signin_form').attr('method'),
          data: form.serialize(),
          beforeSend: function() {
            beforeAjax();
          },
          complete: function(data) {
            $('#signin_submit').prop('disabled', false);
            if (data.status === 200) {
              if (data.responseJSON.redirect){
                window.location.href = data.responseJSON.redirect;
              }
            } else {
              handleResponse(data);
            }
          }
        });
      }
    });
  });
</script>

@endsection