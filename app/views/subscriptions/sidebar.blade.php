<div class="panel-title" style="text-align: center">
    <h4>Select Coverage Area</h4>
</div>
<hr>
    <div class="sidebar-module ">
        <h5><span class="badge alert-success">1</span> Select a state</h5>
        <div class="col-lg-offset-1" id="state-dropdown" >
            {{ Form::select('states', $states, null, [
                    'class' => 'btn btn-default btn-lg dropdown-toggle', 'style' => 'width: 100%'
                ]) }}
        </div>

        <br>
        <h5><span class="badge alert-success">2</span> Select a county</h5>
        <div class="col-lg-offset-1" id="county-dropdown" >
            {{ Form::select('counties', $counties, null, [
                    'class' => 'btn btn-default btn-lg dropdown-toggle', 'style' => 'width: 100%'
                ]) }}
        </div>
    </div>

    <br>
    {{ Form::button('Get Available Regions', [
            'class' => 'btn btn-small btn-primary col-lg-offset-1', 'id' => 'submit-region-info', 'style' => 'width: 92%'
        ]) }}

