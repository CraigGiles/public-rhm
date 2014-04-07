@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="../css/registration.css">
<link rel="stylesheet" href="../css/bootstrap-select.min.css">
@endsection

@section('content')
<div class="row">
  <div class="col-md-6 state-selection">
    <select id="states" class="selectpicker" data-live-search="true"></select>
  </div>
</div>

<div class="row">
  <div class="col-md-6 region-picker">
    <div class="region-list">
      <div class="region-list-filter">
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-search"></i></span>
          <input id="query" class="form-control region-list-filter-input" type="text" placeholder="Search by City or County name">
        </div>
      </div>
      <div id="regions" class="region-filtered">
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div id="selected-regions" class="selected-regions">
    </div>
  </div>
</div>
{{ Form::open() }}
{{ Form::close() }}
@endsection

@section('javascript')
<script id="states_template" type="text/x-handlebars-template">
  @{{#each states}}
    <option value="@{{this.short}}">@{{this.name}}</option>
  @{{/each}}
</script>
<script src="../js/lib/fuse.js" type="application/javascript"></script>
<script src="../js/lib/handlebars-v1.3.0.js" type="application/javascript"></script>
<script src="../js/lib/bootstrap-select.min.js" type="application/javascript"></script>
<!--<script src="../js/lib/ember.min.js" type="application/javascript"></script>-->
<script type="application/javascript">

  /**
   * Variable declarations
   */
  var options = {
    keys: ['search_by', 'type']
  };
  var states = {states: [
    {name:'Alabama', short:'AL'},
    {name:'Alaska', short:'AK'},
    {name:'Arizona', short:'AZ'},
    {name:'Arkansas', short:'AR'},
    {name:'California', short:'CA'},
    {name:'Colorado', short:'CO'},
    {name:'Connecticut', short:'CT'},
    {name:'Delaware', short:'DE'},
    {name:'Florida', short:'FL'},
    {name:'Georgia', short:'GA'},
    {name:'Hawaii', short:'HI'},
    {name:'Idaho', short:'ID'},
    {name:'Illinois', short:'IL'},
    {name:'Indiana', short:'IN'},
    {name:'Iowa', short:'IA'},
    {name:'Kansas', short:'KS'},
    {name:'Kentucky', short:'KY'},
    {name:'Louisiana', short:'LA'},
    {name:'Maine', short:'ME'},
    {name:'Maryland', short:'MD'},
    {name:'Massachusetts', short:'MA'},
    {name:'Michigan', short:'MI'},
    {name:'Minnesota', short:'MN'},
    {name:'Mississippi', short:'MS'},
    {name:'Missouri', short:'MO'},
    {name:'Montana', short:'MT'},
    {name:'Nebraska', short:'NE'},
    {name:'Nevada', short:'NV'},
    {name:'New Hampshire', short:'NH'},
    {name:'New Jersey', short:'NJ'},
    {name:'New Mexico', short:'NM'},
    {name:'New York', short:'NY'},
    {name:'North Carolina', short:'NC'},
    {name:'North Dakota', short:'ND'},
    {name:'Ohio', short:'OH'},
    {name:'Oklahoma', short:'OK'},
    {name:'Oregon', short:'OR'},
    {name:'Pennsylvania', short:'PA'},
    {name:'Rhode Island', short:'RI'},
    {name:'South Carolina', short:'SC'},
    {name:'South Dakota', short:'SD'},
    {name:'Tennessee', short:'TN'},
    {name:'Texas', short:'TX'},
    {name:'Utah', short:'UT'},
    {name:'Vermont', short:'VT'},
    {name:'Virginia', short:'VA'},
    {name:'Washington', short:'WA'},
    {name:'West Virginia', short:'WV'},
    {name:'Wisconsin', short:'WI'},
    {name:'Wyoming', short:'WY'}
  ]};
  var f;
  var regions = [];
  var selected_regions = [];
  var result = [];
  var regions_template;
  var selected_regions_template;

  /**
   * fill out the states template
   */
  var state_template = Handlebars.compile($('#states_template').html());
  $('#states').html(state_template(states));
  $('.selectpicker').selectpicker();
  $('#states').on('change', function(){
    console.log($(this).val());

    $.ajax({
      url: '../geography/search?state='+$(this).val(),
      cache: true,
      success: function(data) {
        //set regions to the JSON from the API
        regions = data;

        //instantiate a new Fuse searching thing
        f = new Fuse(regions, options);
        updateRegionsTemplate();
      }
    });
  });

  /**
   * Grab templates from the server
   */
  (function() {
    $.ajax({
      url: '../js/templates/region.hbs',
      cache: true,
      success: function(data) {
        regions_template = Handlebars.compile(data);
      }
    });
    $.ajax({
      url: '../js/templates/selected_region.hbs',
      cache: true,
      success: function(data) {
        selected_regions_template = Handlebars.compile(data);
      }
    })
  })();


  /**
   * Grab the json from the API/server
   */
//  (function() {
//    $.ajax({
//      url: '../js/CA.json',
//      cache: true,
//      success: function(data) {
//        //set regions to the JSON from the API
//        regions = JSON.parse(data);
//
//        //instantiate a new Fuse searching thing
//        f = new Fuse(regions, options);
//        updateRegionsTemplate();
//      }
//    });
//  })();

  /**
   * Every time the input changes update the regions_template with the search results
   */
  $('#query').on('input', function(){
    //search based on what is in the input box
    $('#regions').scrollTop(0);
    result = f.search($(this).val());

    updateRegionsTemplate();
  });
  function updateRegionsTemplate() {
    if ($('#query').val().length === 0) {
      $('#regions').html(regions_template({regions:regions}));
    } else {
      $('#regions').html(regions_template({regions:result}));
    }
  }

  /**
   * Adding clickability to regions
   */
  function selectRegion(index){
    if (regions[index].selected === undefined){
      selected_regions.push(regions[index]);
      regions[index].selected = true;
      $('button').button('subscribed');
      updateRegionsTemplate();
      updateSelectedRegionTemplate();
    } else {
      regions[index].selected = undefined;
      removeSelectedRegion(index, true);
    }
  }
  function removeSelectedRegion(index, global){
    if (global === undefined) {
      regions[selected_regions[index].id].selected = undefined;
      selected_regions.splice(index, 1);
    } else {
      for (var i=0 ; i<selected_regions.length ; i++) {
        if (selected_regions[i].id === index) {
          selected_regions.splice(i, 1);
          break;
        }
      }
    }
    updateRegionsTemplate();
    updateSelectedRegionTemplate();
  }
  function updateSelectedRegionTemplate(){
    $('#selected-regions').html(selected_regions_template({regions:selected_regions}));
  }
  function isInSelectedRegionList(obj) {
    for (var i=0 ; i<selected_regions.length ; i++) {
      if (obj.search_by === selected_regions[i].search_by &&
          obj.type      === selected_regions[i].type) {
        return true
      }
    }
    return false;
  }

</script>
@endsection