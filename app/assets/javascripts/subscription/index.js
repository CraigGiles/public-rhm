var RHM = RHM || {}
RHM.App = RHM.App || {}

RHM.App.Subscription = {
    statesFilter: function() {
        var element = 'dropdown-states';
        $('.' + element).keyup(function() {
            console.log('#search-'+ element);
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
    RHM.App.Subscription.statesFilter();
    RHM.App.Subscription.countyFilter();
});
