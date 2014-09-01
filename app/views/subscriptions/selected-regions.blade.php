<div class="panel-title" style="text-align: center">
    <h4>Active Regions</h4>
</div>
<div class="well well-sm">
<ul id="selected-regions-container" style="width: 100% list-style-type: none">
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
