@extends('layouts.master')





@section('css')
@endsection





@section('content')
<div class="page-header">
  <h1>
    Select your coverage area to get pricing.<br>
    <small>Sign up now to be part of redhotMAYO’s FREE “Limited Release”</small>
  </h1>
</div>




<div class="row">
  <div id="selections" class="col-md-8">
    <div class="row">
      <div class="col-md-6 state-selection">
        <select style="display: none;" id="states" class="selectpicker" data-live-search="true">
            <option value="0">Select a State...</option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
        </select>
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
    <div class="row">
      <div class="col-md-12" style="margin-top:8px;">
        <button id="submit" type="button" class="btn btn-primary btn-lg pull-right" onclick="submitRegions();">Continue</button>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <img src="http://redhotmayo.com/wp-content/uploads/2014/03/Phone-Tablet-Launch-Page-image-portrait-v2.png" width="100%"/>
  </div>
</div>

{{ Form::open() }}
{{ Form::close() }}

@endsection





<!--JAVASCRIPT-->

@section('javascript')
<script id="states_template" type="text/x-handlebars-template">
  @{{#each states}}
    <option value="@{{this.short}}">@{{this.name}}</option>
  @{{/each}}
</script>

<script id="selected_regions_template" type="text/x-handlebars-template">
  <div>
    @{{#each regions}}
    <p class="selected-region">
      <button type="button" class="btn btn-danger" onclick="removeSelectedRegion(@{{@index}})">Unsubscribe</button>
      @{{this.search_by}}
      <small class="type text-muted">@{{this.type}}, @{{this.state}}</small>
    </p>
    @{{/each}}
  </div>
</script>

<script id="regions_template" type="text/x-handlebars-template">
  <div>
    @{{#each regions}}
    <p id="region-@{{this.id}}" onclick="selectRegion(@{{this.id}})" class="region @{{#if this.selected}}selected@{{/if}}">
      @{{#if this.selected}}
      <button type="button" class="btn btn-danger active">Unsubscribe</button>
      @{{else}}
      <button type="button" class="btn btn-primary">Subscribe</button>
      @{{/if}}
      @{{this.search_by}}
      <small class="type text-muted">@{{this.type}}</small>
    </p>
    @{{/each}}
  </div>
</script>

<script type="application/javascript">

  $(document).ready(function (){
    var height = $(window).height();

    var potentialHeight = height - $('#selections').offset().top - $('.footer').height();

    var extra = $('#selections').height() - $('.region-list').height();

    $('.region-list').height(potentialHeight-extra);
    $('.selected-regions').height(potentialHeight-extra);
    $('.region-filtered').height(potentialHeight-extra-42);
  });


  /**
   * Variable declarations
   */
  var options = {
    keys: ['search_by', 'type']
  };
//  var states = {states: [
//    {name:'Select a State...', short:'0'},
//    {name:'Alabama', short:'AL'},
//    {name:'Alaska', short:'AK'},
//    {name:'Arizona', short:'AZ'},
//    {name:'Arkansas', short:'AR'},
//    {name:'California', short:'CA'},
//    {name:'Colorado', short:'CO'},
//    {name:'Connecticut', short:'CT'},
//    {name:'Delaware', short:'DE'},
//    {name:'Florida', short:'FL'},
//    {name:'Georgia', short:'GA'},
//    {name:'Hawaii', short:'HI'},
//    {name:'Idaho', short:'ID'},
//    {name:'Illinois', short:'IL'},
//    {name:'Indiana', short:'IN'},
//    {name:'Iowa', short:'IA'},
//    {name:'Kansas', short:'KS'},
//    {name:'Kentucky', short:'KY'},
//    {name:'Louisiana', short:'LA'},
//    {name:'Maine', short:'ME'},
//    {name:'Maryland', short:'MD'},
//    {name:'Massachusetts', short:'MA'},
//    {name:'Michigan', short:'MI'},
//    {name:'Minnesota', short:'MN'},
//    {name:'Mississippi', short:'MS'},
//    {name:'Missouri', short:'MO'},
//    {name:'Montana', short:'MT'},
//    {name:'Nebraska', short:'NE'},
//    {name:'Nevada', short:'NV'},
//    {name:'New Hampshire', short:'NH'},
//    {name:'New Jersey', short:'NJ'},
//    {name:'New Mexico', short:'NM'},
//    {name:'New York', short:'NY'},
//    {name:'North Carolina', short:'NC'},
//    {name:'North Dakota', short:'ND'},
//    {name:'Ohio', short:'OH'},
//    {name:'Oklahoma', short:'OK'},
//    {name:'Oregon', short:'OR'},
//    {name:'Pennsylvania', short:'PA'},
//    {name:'Rhode Island', short:'RI'},
//    {name:'South Carolina', short:'SC'},
//    {name:'South Dakota', short:'SD'},
//    {name:'Tennessee', short:'TN'},
//    {name:'Texas', short:'TX'},
//    {name:'Utah', short:'UT'},
//    {name:'Vermont', short:'VT'},
//    {name:'Virginia', short:'VA'},
//    {name:'Washington', short:'WA'},
//    {name:'West Virginia', short:'WV'},
//    {name:'Wisconsin', short:'WI'},
//    {name:'Wyoming', short:'WY'}
//  ]};
  var f;
  var regions_cache = {};
  var regions = [];
  var selected_regions = [];
  var result = [];
  var state = '';
//  var regions_template;
//  var selected_regions_template;

  /**
   * fill out the states template
   */
//  var state_template = Handlebars.compile($('#states_template').html());
//  $('#states').html(state_template(states));
  $('.selectpicker').selectpicker();
  $('#states').on('change', function(){
    if ($(this).val() !== '0') {
      //save the current state so you dont have to look it up all the time
      state = $(this).val();

      if (regions_cache[state] === undefined) {

        //go get state data
        $.ajax({
          url: '../geography/search?state=' + $(this).val(),
          cache: true,
          beforeSend: function() {
            $('#query').val('');
            $('#regions').empty();
            $('#regions').addClass('loading');
          },
          complete: function (data) {
            /**
             * ON SUCCESS
             */
            if (data.status === 200) {

              //set regions to the JSON from the API
              regions_cache[state] = data.responseJSON;
              regions = regions_cache[state];

              $('#regions').removeClass('loading');

              //instantiate a new Fuse searching thing
              f = new Fuse(regions, options);
              updateRegionsTemplate();
            }
            /**
             * ELSE HANDLE ERRORS
             */
            else {
              handleResponse(data);
            }
          }
        });
      }

      //the data is in the cache, get it from there.
      else {
        regions = regions_cache[state];

        //instantiate a new Fuse searching thing
        f = new Fuse(regions, options);
        updateRegionsTemplate();
      }
    } else {
      regions = [];
      updateRegionsTemplate()
    }
  });

  /**
   * Setup the templates for the regions.
   */
  var regions_template = Handlebars.compile($('#regions_template').html());
  var selected_regions_template = Handlebars.compile($('#selected_regions_template').html());

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
    //if the region isnt selected right now
    if (regions[index].selected === undefined){
      selected_regions.push(regions[index]);
      regions[index].selected = true;
      regions[index].state = state;
      regions[index].state = $('#states').val();
      var elm = $('#region-'+index);
      var button = elm.children('button');
      button.html('Unsubscribe');
      button.addClass('btn-danger');
      button.addClass('active');
      elm.addClass('selected');
      updateSelectedRegionTemplate();
      //updateRegionsTemplate();

    }
    //if the region is selected
    else {
      removeSelectedRegionByGlobalID(index);
    }
  }
  function removeSelectedRegion(index){
    regions_cache[selected_regions[index].state][selected_regions[index].id].selected = undefined;
    selected_regions.splice(index, 1);

    updateSelectedRegionTemplate();
    updateRegionsTemplate();
  }
  function removeSelectedRegionByGlobalID(index) {
    for (var i=0 ; i<selected_regions.length ; i++) {
      if (selected_regions[i].id === index && selected_regions[i].state === state) {
        regions[index].selected = undefined;
        selected_regions.splice(i, 1);
        break;
      }
    }

    updateSelectedRegionTemplate();
    updateRegionsTemplate();
  }
  function updateSelectedRegionTemplate(){
    $('#selected-regions').html(selected_regions_template({regions:selected_regions}));
  }


  /**
   * Form submission
   */
  //setup failure popover

  //submit the form with ajax then redirect
  function submitRegions() {
    $('#submit').popover('destroy');
    $.ajax({
      url: 'subscribe',
      type: 'POST',
      dataType: 'json',
      data: JSON.stringify({_token: $('[name=_token').val(), regions:selected_regions}),
      cache: true,
      beforeSend: function(){
        $('#submit').prop('disabled', true);
      },
      complete: function(data) {
        $('#submit').prop('disabled', false);
        if (data.status === 401 || data.status === 200) {
          if (data.responseJSON.redirect){
            window.location.href = data.responseJSON.redirect;
          }
        } else {
          handleResponse(data);
        }
      }
    });
  }


</script>
@endsection