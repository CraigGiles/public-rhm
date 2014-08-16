@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/subscription/index.js'); }}
    @endsection

    @section("content")

        <div class="page-header">
          <h1>
            Select your coverage area to get pricing.
            <div class="price-list"></div>
          </h1>
        </div>

        <div class="dropdown">
            <button class="btn btn-default btn-lg dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown">
                Select a State...
                <span class="caret"></span>
            </button>

            <ul class="dropdown-menu" role="menu">
                <? //Search bar inside the dropdown menu for filtering states?>
                {{ Form::open(['class' => 'navbar-form navbar-left', 'role' => 'search']) }}
                  <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                  </div>
                {{ Form::close() }}

                <? //poplate the dropdown menu with all the available states ?>
                @foreach($states as $state)
                    <li role="presentation"><a role="menuitem">{{{ $state }}}</a></li>
                @endforeach
            </ul>

        </div>
    @endsection
@stop
