var RHM = RHM || {}
RHM.App = RHM.App || {}

RHM.App.Registration = {
    /**
     * Registers click events for all clickable elements on the page
     */
    registerClickEvents: function() {
        RHM.App.Registration.submitRegistrationClick();
    },

    submitRegistrationClick: function() {
        $('#registration-submit').click(function() {
            mixpanel.track(
                "Registration Completed",
                { "User Name": "????" } //Get the user name
            );
        });
    },

    init: function() {
        RHM.App.Registration.registerClickEvents();
    }
}

$(document).ready(function (){
    RHM.App.Registration.init();
});