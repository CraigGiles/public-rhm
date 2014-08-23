var RHM = RHM || {}
RHM.App = RHM.App || {}

RHM.App.Subscription = {
    registerClickEvents: function() {
        RHM.App.Subscription.stateItemClick();
    },

    registerFilterEvents: function() {
        RHM.App.Subscription.statesFilter();
        RHM.App.Subscription.countyFilter();
    },

    stateItemClick: function() {
        $('.state-item').click(function() {
            RHM.App.Subscription.loadCountiesForState($(this).text());
        });
    },

    loadCountiesForState: function(state) {
        $.ajax({
            dataType: "json",
            url: '../geography/search?state=' + state,
            cache: true,
            beforeSend: function() {
                //beforeAjax();
                //$('#query').val('');
                //$('#regions').empty();
                //$('#regions').addClass('loading');
            },
            complete: function (data) {
                if (data.status === 200) {
                    var json = data.responseJSON;
                    // set the regions to the JSON from the API

                    // set the counties to the correct values
                    RHM.App.Subscription.addCounties(json);
                }
            }
        });

    },

    addCounties: function(json) {
        // clear counties
        $('.dropdown-counties li').each(function() {
            $(this).remove();
        });

        // add new counties
        $.each(json, function(index) {
            var county = json[index].county;
            var type = json[index].type;
            if (type == "county") {
                $('#dropdown-counties-list').append('<li role="presentation"><a class="county-item" role="menuitem">' + RHM.App.Subscription.toTitleCase(county) + '</a></li>');
            }
        });
    },

    toTitleCase: function(str) {
        return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    },

    statesFilter: function() {
        var element = 'dropdown-states';
        $('.' + element).keyup(function() {
            RHM.App.Subscription.filter(element, $('#search-'+ element));
        });
    },

    countyFilter: function() {
        var element = 'dropdown-counties';
        $('.' + element).keyup(function() {
            RHM.App.Subscription.filter(element, $('#search-'+ element));
        });
    },

    filter: function(element, object) {
        var valThis = object.val();
        $('.' + element + ' li').each(function() {
            var text = $(this).text().toLowerCase();
            (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
        });
    }
}

$(document).ready(function (){
    RHM.App.Subscription.registerClickEvents();
    RHM.App.Subscription.registerFilterEvents();
});
