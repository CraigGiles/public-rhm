@extends('layouts.master')

    @section('black-bar-text')
        My Dashboard
    @endsection

@section('content')
    @include('partials.errors')

    <div style="margin-top: 30px; margin-left: 30px;">
        {{ link_to('logout', 'Logout', ['class' => 'btn btn-primary btn-lg']) }}
    	{{ link_to('subscribe', 'Update Coverage Area', ['class' => 'btn btn-primary btn-lg']) }}

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
            <strong>Thank you for using redhotMAYO!</strong>
        </p>
        <div style = "width:55%; padding-right: 20px">
            <p>
                To access your FREE New Restaurant Openings download your redhotMAYO app at the <a href="https://play.google.com/store/apps/details?id=com.redhotmayo.accountManager&hl=en">Google Play Store</a>.  Log into the app with the same username and password you used to sign up.  You can always add/subtract cities to adjust pricing & the amount of restaurant openings you get.  Remember, failure is not an option! 
            </p>
        </div>
	</div>

	<div class="col-md-4 pull-right app-image" style="margin-top: -270px; float: right;">
	    <img src="assets/tablets.png"/>
	</div>
@endsection

@stop

@section('javascript')
{{ HTML::script('assets/javascripts/dashboard/dashboard.js') }}
@endsection
