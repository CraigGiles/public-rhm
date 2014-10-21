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
	    <font style="color: #ffffff; font-family:robotoregular;">
	        {{ link_to('billing/cancel', 'Cancel my Subscription') }}
	    </font>
	
	</div>

	<div class="col-md-4 pull-right app-image" style="margin-top: -140px">
	    <img src="assets/tablets.png"/>
	</div>
@endsection

@stop

@section('javascript')
{{ HTML::script('assets/javascripts/dashboard/dashboard.js') }}
@endsection
