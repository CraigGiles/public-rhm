var RHM = RHM || {}
RHM.App = RHM.App || {}

RHM.App.Subscription = {
    /**
     * Registers click events for all clickable elements on the page
     */
    registerClickEvents: function() {
        RHM.App.Subscription.regionItemButtonClick();
        RHM.App.Subscription.submitRegionInfoClick();
        RHM.App.Subscription.submitSubscriptionClick();
    },

    regionItemButtonClick: function() {
        $('.region-item-button-add').click(RHM.App.Subscription.addItemToSelectedRegions);
        $('.region-item-button-remove').click(RHM.App.Subscription.removeFromSelectedRegions);
    },

    submitRegionInfoClick: function() {
        $('#submit-region-info').click(function() {
            var state = RHM.App.Subscription.currentState;
            var county = RHM.App.Subscription.currentCounty;

            $.ajax({
                dataType: "json",
                url: '../geography/search?state=' + state + '&county=' + county,
                cache: true,
                complete: function (data) {
                    if (data.status === 200) {
                        var json = data.responseJSON;
                        RHM.App.Subscription.setAvailableRegions(json);
                    }
                }
            });
        });
    },

    submitSubscriptionClick: function() {
        $('#subscription-submit').click(function() {
            var regions = RHM.App.Subscription.getSubscribedRegions();

            $.ajax({
                url: '/subscribe',
                type: 'POST',
                dataType: 'json',
                data: JSON.stringify({_token: $('[name=_token]').val(), regions: regions}),
                cache: true,
                beforeSend: function () {
//                    beforeAjax();
//                    $('#submit').prop('disabled', true);
                },
                complete: function (data) {
                    if (data.status === 401 || data.status === 200) {
                        if (data.responseJSON.redirect) {
                            window.location.href = "http://localhost:8000/" + data.responseJSON.redirect;
                            alert(window.location.href);
                        }
                    } else {
                        alert('status was bad!!!!');
                        RHM.App.Subscription.errorHandler(data);
                    }
                }
            });
        });
    },

    registerFilterEvents: function() {
        RHM.App.Subscription.stateDropdownChangeEvent();
        RHM.App.Subscription.countyDropdownChangeEvent();
    },

    removeFromSelectedRegions: function() {
        var p = $(this).closest('li').detach();

        var newItem = {};
        newItem.city = p.find('.region-city').text();
        newItem.county = p.find('.region-county').text();
        newItem.state = p.find('.region-state').text();

        RHM.App.Subscription.region_items = jQuery.grep(RHM.App.Subscription.region_items, function(value) {
            return value.city != newItem.city && value.county != newItem.county && value.state != newItem.state;
        });

        RHM.App.Subscription.updateTotal();
    },

    addItemToSelectedRegions: function() {
        var oldItem = $(this).closest('li');

        var city = oldItem.find('.region-city').text();
        var county = oldItem.find('.region-county').text();
        var state = oldItem.find('.region-state').text();
        var region = {city: city, state: state, type:"city", county: county};

        // if region wasn't found, add it
        var newArr = $.grep(RHM.App.Subscription.region_items, function(obj) {
            return obj.city === city && obj.county === county && obj.state === state;
        });

        if(newArr.length === 0) {
            RHM.App.Subscription.region_items.push(region);

            var item = RHM.App.Subscription.getNewRegionItem(city, county, state, 'region-item-button-remove', '+');

            RHM.App.Subscription.selectedRegions.append(item.html());
            RHM.App.Subscription.selectedRegions.unbind('click', RHM.App.Subscription.removeFromSelectedRegions);
            RHM.App.Subscription.selectedRegions.on('click', '.region-item-button-remove', RHM.App.Subscription.removeFromSelectedRegions);
        }

        RHM.App.Subscription.updateTotal();
    },

    getNewRegionItem: function(city, county, state, className, buttonText) {
        var p = RHM.App.Subscription.regionItemTemplate.clone();
        var fCity = RHM.Utils.StringHelper.toTitleCase(city);
        var fCounty = RHM.Utils.StringHelper.toTitleCase(county);

        p.find('.region-city').text(fCity);
        p.find('.region-county').text(fCounty);
        p.find('.region-state').text(state.toUpperCase());
        p.find('.btn').removeClass('region-item-button-add');
        p.find('.btn').removeClass('region-item-button-remove');
        p.find('.btn').addClass(className);
        p.find('.btn').text(buttonText);

        return p;
    },

    setAvailableRegions: function(json) {
        var element = RHM.App.Subscription.availableRegions;
        var state = RHM.App.Subscription.currentState;
        var county = RHM.App.Subscription.currentCounty;
        element.html('');

        $.each(json, function(index) {
            var type = json[index].type;

            if (type == "city") {
                var search_by = json[index].search_by;
                var newRegionItem = RHM.App.Subscription.getNewRegionItem(search_by, county, state, 'region-item-button-add', '+');

                $(element).append(newRegionItem.html());
            }
        });

        element.on('click', '.region-item-button-add', RHM.App.Subscription.regionItemButtonClick());
    },

    stateDropdownChangeEvent: function() {
        $("#state-dropdown").change(function() {
            RHM.App.Subscription.currentState = $('#state-dropdown :selected').val();
            RHM.App.Subscription.populateCountyDropdown(RHM.App.Subscription.currentState);
        });
    },

    countyDropdownChangeEvent: function() {
        $("#county-dropdown").change(function() {
            RHM.App.Subscription.currentCounty = $('#county-dropdown :selected').val();
        });
    },

    populateCountyDropdown: function(state) {
        $.ajax({
            dataType: "json",
            url: '../geography/search?state=' + state,
            cache: true,
            complete: function (data) {
                if (data.status === 200) {
                    var json = data.responseJSON;
                    RHM.App.Subscription.setCountiesDropdown(json);
                    RHM.App.Subscription.currentCounty = $('#county-dropdown :selected').val();
                }
            }
        });
    },

    setCountiesDropdown: function(json) {
        var countyDropdown = $('#county-dropdown select');
        countyDropdown.html('');

        // add new counties
        $.each(json, function(index) {
            var county = json[index].county;
            var type = json[index].type;

            if (type == "county") {
                countyDropdown.append('<option value="'+ county +'">'+ county +'</option>');

            }
        });
    },

    getSubscribedRegions: function() {
        var container = RHM.App.Subscription.selectedRegions;
        var items = [];

        container.find('li').each(function() {
            var region = $(this);
            var city = region.find('.region-city').text();
            var county = region.find('.region-county').text();
            var state = region.find('.region-state').text();
            var item = {city: city, state: state, type:"city", county: county};
            items.push(item);
        });

        return items;
    },

    updateTotal: function() {
        var subRegion = RHM.App.Subscription.totalRegion;
        var items = RHM.App.Subscription.getSubscribedRegions();

        $.ajax({
            url:'/subscribe/price',
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({regions:items}),
            cache: true,
            complete: function(data) {
                subRegion.text("Total: $" + parseFloat(data.responseJSON.message)/100);
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

    setRegionItems: function() {
        RHM.App.Subscription.selectedRegions.find('li').each(function() {
            var region = $(this);
            var city = region.find('.region-city').text();
            var county = region.find('.region-county').text();
            var state = region.find('.region-state').text();
            var item = {city: city, state: state, type:"city", county: county};

            // if region wasn't found, add it
            var newArr = $.grep(RHM.App.Subscription.region_items, function(obj) {
                return obj.city === city && obj.county === county && obj.state === state;
            });

            if(newArr.length === 0) {
                RHM.App.Subscription.region_items.push(item);
            }
        });

        RHM.App.Subscription.updateTotal();
    },

    init: function() {
        RHM.App.Subscription.registerClickEvents();
        RHM.App.Subscription.registerFilterEvents();
        RHM.App.Subscription.setRegionItems();

        this.updateTotal();
    },


    errorHandler: function (data, redirect) {
        /**
         * Handle redirection as long as the page is not where we are right now.
         */
        if (redirect !== false && data.responseJSON.redirect !== window.location.pathname.replace('/', '')) {
            window.location.href = data.responseJSON.redirect;
        }

        /**
         * make sure message value is an array of objects
         */
        if (typeof data.responseJSON.message === 'string') {
            data.responseJSON.message = [
                {err: data.responseJSON.message}
            ];
        }


        /**
         * 4xx Client Error
         */
        if (data.status >= 400 && data.status <= 499) {
            var errors = data.responseJSON;
            alert('Client Error');
//            errors.type = 'warning';
//            $('#warnings').html(warning_template(errors));
//            $('#regions').empty();
        }

        /**
         * 5xx Server Error
         */
        else if (data.status >= 500 && data.status <= 599) {
            var errors = data.responseJSON;
            alert('Server Error');
//            errors.type = 'danger';
//            $('#warnings').html(warning_template(errors));
//            $('#regions').empty();
        }
    }


}

$(document).ready(function (){
    RHM.App.Subscription.regionItemTemplate = $('#region-item-template');
    RHM.App.Subscription.selectedRegions = $('#selected-regions-container');
    RHM.App.Subscription.availableRegions = $('#available-regions-container');
    RHM.App.Subscription.totalRegion = $('#subscription-total');

    RHM.App.Subscription.currentState = $('#state-dropdown :selected').val();
    RHM.App.Subscription.currentCounty = $('#county-dropdown :selected').val();
    RHM.App.Subscription.region_items = [];

    RHM.App.Subscription.init();
});

