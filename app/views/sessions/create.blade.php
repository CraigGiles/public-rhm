@extends('layouts.master')

@section('black-bar-text')
Login to redhotMAYO below:
@endsection

@section('content')
@include('partials.errors')

<div class="col-lg-4">
    @include('layouts.login')
</div>

<div class="col-md-4 main-right" style="margin-left:20px">
    <h1>Stay Tuned!</h1>
    <p>redhotMAYO continually improves our tools to help foodservice sales professionals succeed!<p>
    <p>We strive to keep you informed; keep a lookout for the following new features in upcoming months:<p>
    <img src="assets/check.jpg"> <strong>Add Your Accounts - </strong> Manually or import<p>
    <img src="assets/check.jpg"> <strong>Proximity Search - </strong> Instantly fing accounts close to your current location<p>
    <img src="assets/check.jpg"> <strong>Export - </strong> Select and export acounts to popular formats like xls and .pdf<p>
</div>

<div id="right-bar-login">
		<img src="assets/tablets.png">
</div>

@endsection