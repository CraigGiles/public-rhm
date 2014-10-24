@extends('layouts.master')

    @section('black-bar-text')
        My Dashboard
    @endsection

@section('content')
    @include('partials.errors')

    <div style="margin-top: 30px; margin-left: 30px;">
        {{ link_to('logout', 'Logout', ['class' => 'btn btn-primary btn-lg']) }}
    	{{ link_to('subscribe', 'Update My Regions', ['class' => 'btn btn-primary btn-lg']) }}

    	@if($canUpload)
            {{ link_to('administration', 'Upload Leads', ['class' => 'btn btn-primary btn-lg']) }}
		@endif

    	<br>
    	<br>
	    <font style="color: #ffffff; font-family:roboto; font-weight: 400;">
	        {{ link_to('billing/cancel', 'Cancel my Subscription') }}
	    </font>

        <div class="break"></div>
        <p style = "display:block; padding-top:40px">
            Thank you for using redhotMAYO!
        </p>
        <p>
            To get access to all your leads, please download our app at the  <a href="https://play.google.com/store/apps/details?id=com.redhotmayo.accountManager&hl=en">Google Play Store</a>
	    </p>
        <p>
            You can log into the app with the same username and password you used to sign up
        </p>
	</div>

	<div class="col-md-4 pull-right app-image" style="margin-top: -270px; float: right;">
	    <img src="assets/tablets.png"/>
	</div>
@endsection

@stop

@section('javascript')
{{ HTML::script('assets/javascripts/dashboard/dashboard.js') }}
@endsection
