@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section("content")
    <div class="page-header">
      <h1>
        Select your coverage area to get pricing.
        <div class="price-list"></div>
      </h1>
    </div>
        @include('subscriptions.v2.sidebar.sidebar', $sidebar)


    @endsection
@stop
