@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="../css/registration.css">
@endsection

@section('content')
<div class="row">
  <div class="col-md-6 state-selection">
    <div class="btn-group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        State <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
      </ul>
    </div>
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

  <div class="col-md-6 selected">
    <div id="region-selected" class="region-selected">
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script src="../js/lib/fuse.js" type="application/javascript"></script>
<script src="../js/lib/handlebars-v1.3.0.js" type="application/javascript"></script>
<!--<script src="../js/lib/ember.min.js" type="application/javascript"></script>-->
<script type="application/javascript">
  //Variable declarations
  var options = {
    keys: ['search_by', 'type']
  };
  var f;
  var regions;
  var template;
  var region_template;

  /**
   * Grab the json from the API/server
   */
  (function() {
    $.ajax({
      url: '../js/CA.json',
      cache: true,
      success: function(data) {
        //set regions to the JSON from the API
        regions = JSON.parse(data);

        //instantiate a new Fuse searching thing
        f = new Fuse(regions, options);
        //grab the regions template from the server to make things easier
        $.ajax({
          url: '../js/templates/region.hbs',
          cache: true,
          success: function(data) {
            //set and compile the template
            region_template   = data;
            template = Handlebars.compile(region_template);
            //place the full regions list in the template to start with
            $('#regions').html(template({regions:regions}));
          }
        });
      }
    });
  })();

  /**
   * Every time the input changes update the template with the search results
   */
  $('#query').on('input', function(){
    //search based on what is in the input box
    var result = f.search($(this).val());

    //if the search box in empty, return the full region list
    if ($(this).val().length === 0) {
      $('#regions').html(template({regions:regions}));
    } else {
      $('#regions').html(template({regions:result}));
    }

  });

</script>
@endsection