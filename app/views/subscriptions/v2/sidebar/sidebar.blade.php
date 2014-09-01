@section("javascript")
    {{ HTML::script('assets/string-helper.js'); }}
    {{ HTML::script('assets/subscription/index.js'); }}
@endsection

{{ Form::open(['url' => '/subscribe/update', 'method' => 'post']) }}
<div class="col-xs-3 sidebar-module well">
    <div class="sidebar-module ">
        <div class="state-dropdown"><h5><span class="badge alert-success">1</span> Select a state</h5></div>
        <div class="col-lg-offset-1" >
            {{ Form::select('states', $states, $state, ['class' => 'btn btn-default btn-lg dropdown-toggle', 'style' => 'width: 100%']) }}
        </div>

        <br>
        <div class="county-dropdown"><h5><span class="badge alert-success">2</span> Select a county</h5></div>
        <div class="col-lg-offset-1" >
            {{ Form::select('counties', $counties, $county, ['class' => 'btn btn-default btn-lg dropdown-toggle', 'style' => 'width: 100%']) }}
        </div>
    </div>

    <br>
    <div class="county-dropdown"><h5><span class="badge alert-success">3</span> Get region information</h5></div>
    {{ Form::submit('Submit', ['class' => 'btn btn-small btn-primary col-lg-offset-1', 'style' => 'width: 92%']) }}
</div>

