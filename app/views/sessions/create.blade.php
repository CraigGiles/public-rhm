@extends('layouts.master')

@section('header-two')
Login to redhotMAYO below:
@endsection

@section('content')
@include('partials.errors')

<div class="col-lg-4">
    @include('layouts.login')
</div>

<div class="col-md-4 main-right">
    <h1>Why <font color="#8dc73f">redhot</font><font color="#ff5050">MAYO</font>?</h1>
    <p>redhotMAYO's new "HOT" module gives you the ability to integrate with suppliers and brokers in ways never before possible, including:<p>
    <img src="assets/check.jpg"> Share key account details with partners to collaborate in tag-team selling<p>
    <img src="assets/check.jpg"> Automate your sampling processes to get samples to operations and close new sales faster<p>
    <img src="assets/check.jpg"> And much more!<p>
</div>

<div class="col-md-4 pull-right app-image">
    <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
</div>
@endsection