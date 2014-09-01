<div class="well well-sm">

<div class="panel-title" style="text-align: center">
    <h4>Active Regions</h4>
</div>
<hr>

<ul id="selected-regions-container" style="width: 100%; min-height: 450px; height: 100%; list-style-type: none">
    @foreach($activeSubscriptions as $reg)
    @include('subscriptions.partials.region_item', [
             'buttonColor' => 'danger',
             'addOrRemove' => 'remove',
             'city' => $reg->getCity(),
             'state' => $reg->getState(),
         ])
    @endforeach
</ul>
</div>
