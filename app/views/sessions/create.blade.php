@extends('layouts.master')

@section('content')

<div class="page-header">
@if (Session::has('flash_message'))
    <div class="alert alert-info">
        {{ Session::get('flash_message') }}
        @include('partials.errors')
    </div>
@endif
  <h1>
    Log In
  </h1>
</div>


  <div class="col-lg-5">
    @include('layouts.login')
  </div>

  <div class="col-md-7">
    redhotMAYO's new "HOT" module gives you the ability to integrate with suppliers and brokers in ways never before possible, including:
    <p>
        <li>Share key account details with partners to collaborate in tag team selling</li>
        <li>Automate your sampling processes to get samples to operators and close new sales faster</li>
        <li>Other marketing messages....</li>
    </p>
  </div>


@endsection