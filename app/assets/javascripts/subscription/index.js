var RHM = RHM || {}
RHM.App = RHM.App || {}

RHM.App.Subscription = {
    statesFilter: function() {
        $('#dropdown-states').keyup(function() {
            RHM.App.Subscription.filter($(this));
        });
    },

    countyFilter: function() {
        $('#dropdown-counties').keyup(function() {
            RHM.App.Subscription.filter($(this));
        });
    },

    filter: function(object) {
        var valThis = object.val();

        $( "li" ).each(function() {
            var text = $(this).text().toLowerCase();
            (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
        });
    }
}

$(document).ready(function (){
    RHM.App.Subscription.statesFilter();
    RHM.App.Subscription.countyFilter();
});
