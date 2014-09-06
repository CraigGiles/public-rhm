<div class="well well-sm">

<div class="panel-title" style="text-align: center">
    <h4>Selected Regions</h4>
</div>
<hr>

<ul id="selected-regions-container" style="width: 100%; min-height: 450px; max-height: 450px; overflow-y: scroll; height: 100%; list-style-type: none">
    @foreach($activeSubscriptions as $reg)
    @include('subscriptions.partials.region_item', [
             'buttonColor' => 'success',
             'addOrRemove' => 'remove',
             'city' => $reg->getCity(),
             'county' => $reg->getCounty(),
             'state' => $reg->getState(),
         ])
    @endforeach
</ul>
</div>
