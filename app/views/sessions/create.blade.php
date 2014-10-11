@extends('layouts.master')

@section('content')
<div class="page-header">
    @include('partials.errors')
</div>

<div class="col-lg-4">
    @include('layouts.login')
</div>

<div class="col-md-5 main-right">
    <h1>Why <font color="#8dc73f">redhot</font><font color="#ff5050">MAYO</font>?</h1><p>redhotMAYO's new "HOT" module gives you the ability to integrate with suppliers and brokers in ways never before possible, including:<p>
    <img src="assets/check.jpg"> Share key account details with partners to collaborate in tag-team selling<p>
    <img src="assets/check.jpg"> Automate your sampling processes to get samples to operations and close new sales faster<p>
    <img src="assets/check.jpg"> And much more!<p>
</div>

<div id="col-md-2 right-bar">
		<img src="assets/tablets.png">
</div>

@endsection