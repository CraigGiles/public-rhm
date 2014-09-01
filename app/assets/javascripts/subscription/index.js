var RHM = RHM || {}
RHM.App = RHM.App || {}

RHM.App.Subscription = {
    registerClickEvents: function() {
        //RHM.App.Subscription.stateItemClick();
        //RHM.App.Subscription.countyItemClick();
        RHM.App.Subscription.regionItemButtonClick();
    },

    registerFilterEvents: function() {
        RHM.App.Subscription.statesSearchBoxFilter();
        RHM.App.Subscription.countySearchBoxFilter();
    },

    regionItemButtonClick: function() {
        $('.region-item-button-add').click(function() {
            var oldItem = $(this).closest('li');
            var city = oldItem.find('.region-city').text();
            var state = oldItem.find('.region-state').text();
            var item = RHM.App.Subscription.getNewRegionItem(city, state, 'region-item-button-remove', '-');
;
            RHM.App.Subscription.selectedRegions.append(item.html());
            RHM.App.Subscription.selectedRegions.on('click', '.region-item-button-remove', function() {
                $(this).closest('li').detach();
            });
        });

        $('.region-item-button-remove').click(function() {
            $(this).closest('li').detach();
        });
    },

    getNewRegionItem: function(city, state, className, buttonText) {
        var p = RHM.App.Subscription.regionItemTemplate.clone();
        var fCity = RHM.Utils.StringHelper.toTitleCase(city);

        p.find('.region-city').text(fCity);
        p.find('.region-state').text(state.toUpperCase());
        p.find('.btn').removeClass('region-item-button-add');
        p.find('.btn').addClass(className);
        p.find('.btn').text(buttonText);

        return p;
    },

    //stateItemClick: function() {
    //    $('.state-item').click(function() {
    //        var sub     = RHM.App.Subscription;
    //        var state   = RHM.App.Subscription.state = $(this).text();
    //        var element = $('#dropdown-states-button');
    //
    //        sub.loadCountiesForState(state);
    //
    //        element.text(state);
    //        element.append(' <span class="caret"></span>');
    //    });
    //},
    //
    //countyItemClick: function() {
    //    $('.county-item').click(function() {
    //        var sub     = RHM.App.Subscription;
    //        var county   = RHM.App.Subscription.county = $(this).text();
    //        var element = $('#dropdown-counties-button');
    //
    //        sub.filterCitiesByCounty(county);
    //
    //        element.text(county);
    //        element.append(' <span class="caret"></span>');
    //    });
    //},
    //
    //regionItemButtonClick: function(btn) {
    //    $('.region-item-button').click(function() {
    //
    //    });
    //},
    //
    //loadCountiesForState: function(state) {
    //    $.ajax({
    //        dataType: "json",
    //        url: '../geography/search?state=' + state,
    //        cache: true,
    //        complete: function (data) {
    //            if (data.status === 200) {
    //                var json = data.responseJSON;
    //                // set the regions to the JSON from the API
    //                RHM.App.Subscription.setAvailableRegions(json);
    //
    //                // set the counties to the correct values
    //                RHM.App.Subscription.setCountiesDropdown(json);
    //            }
    //        }
    //    });
    //},
    //
    //filterCitiesByCounty: function(county) {
    //    var element = $('#available-regions');
    //    var county = RHM.App.Subscription.county;
    //
    //    element.children().each(function() {
    //        var elm = $(this);
    //        if (elm.find('.search-term').text().toLowerCase() != county.toLowerCase()) {
    //            elm.hide();
    //        }
    //    });
    //},
    //
    //setAvailableRegions: function(json) {
    //    var element = $('#available-regions');
    //    var state = RHM.App.Subscription.state;
    //    element.html('');
    //
    //    $.each(json, function(index) {
    //        var type = json[index].type;
    //        var search_by = json[index].search_by;
    //
    //        $(element).append(RHM.App.Subscription.getRegionItem(search_by, type, state, '+'));
    //    });
    //},
    //
    //getRegionItem: function(text, type, state, buttonText) {
    //    var p = RHM.App.Subscription.regionItemTemplate;
    //    var titleCaseText = RHM.Utils.StringHelper.toTitleCase(text);
    //
    //    p.find('.search-term').text(titleCaseText);
    //    p.find('.region-type').text(type + ", " + state);
    //    p.find('.region-item-button').text(buttonText);
    //    p.selected = false;
    //
    //    return p.clone();
    //},
    //
    //setCountiesDropdown: function(json) {
    //    // clear counties
    //    $('.dropdown-counties li').each(function() {
    //        $(this).remove();
    //    });
    //
    //    // add new counties
    //    $.each(json, function(index) {
    //        var county = json[index].county;
    //        var type = json[index].type;
    //        if (type == "county") {
    //            $('#dropdown-counties-list')
    //                .append(
    //                    '<li role="presentation"><a class="county-item" role="menuitem">'
    //                        + RHM.Utils.StringHelper.toTitleCase(county) + '</a></li>'
    //                );
    //        }
    //    });
    //
    //    RHM.App.Subscription.registerClickEvents();
    //    RHM.App.Subscription.registerFilterEvents();
    //},

    statesSearchBoxFilter: function() {
        $("#state-dropdown").change(function() {
            RHM.App.Subscription.currentState = $('#state-dropdown :selected').text();
            RHM.App.Subscription.populateCountyDropdown(RHM.App.Subscription.currentState);
        });
    },

    countySearchBoxFilter: function() {
        $("#county-dropdown").change(function() {
            RHM.App.Subscription.currentCounty = $('#county-dropdown :selected').text();
            //RHM.App.Subscription.populateAvailableCities();
        });
    },

    populateCountyDropdown: function(state) {
        console.log(state);
        $.ajax({
            dataType: "json",
            url: '../geography/search?state=' + state,
            cache: true,
            complete: function (data) {
                if (data.status === 200) {
                    var json = data.responseJSON;
                    RHM.App.Subscription.setCountiesDropdown(json);
                }
            }
        });

        //populate counties dropdown
        //reinitialize the click / listen / events
    },

    setCountiesDropdown: function(json) {
        var dropdown = $('#county-dropdown select');
        dropdown.html('');

        // add new counties
        $.each(json, function(index) {
            var county = json[index].county;
            var type = json[index].type;
            if (type == "county") {
                dropdown.append('<option value="'+ county +'">'+ county +'</option>');
            }
        });
    },

    searchBoxFilter: function(element, object) {
        var valThis = object.val();
        $('.' + element + ' li').each(function() {
            var text = $(this).text().toLowerCase();
            (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
        });
    },

    init: function() {
        RHM.App.Subscription.registerClickEvents();
        RHM.App.Subscription.registerFilterEvents();
    }

}

$(document).ready(function (){
    RHM.App.Subscription.regionItemTemplate = $('#region-item-template');
    RHM.App.Subscription.selectedRegions = $('#selected-regions-container');
    //console.log(RHM.App.Subscription.selectedRegions.html());

    //RHM.App.Subscription.selectedRegions.append(newItem.html());
    RHM.App.Subscription.init();
});

