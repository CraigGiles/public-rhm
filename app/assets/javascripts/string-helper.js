var RHM = RHM || {}
RHM.Utils = RHM.Utils || {}

RHM.Utils.StringHelper = {
    toTitleCase: function (str) {
        return str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }
}
