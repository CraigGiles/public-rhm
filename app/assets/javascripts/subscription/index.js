var RHM = RHM || {}
RHM.App = RHM.App || {}
RHM.App.Subscription = RHM.App.Subscription || {}

$(document).ready(function (){
});

$('#dropdown-states').keyup(function(){
    var valThis = $(this).val();

    $( "li" ).each(function() {
        var text = $(this).text().toLowerCase();
        (text.indexOf(valThis) > -1) ? $(this).show() : $(this).hide();
    });
});


