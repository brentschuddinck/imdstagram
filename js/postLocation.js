var App = {

    KEY: "AIzaSyABbM6WxNpdxftCgQzzNUkfeIiLBHkeZdM",
    lat: "",
    lng: "",

    init: function () {
        App.getLocation();
    },

    getLocation: function () {
        // get the current user posisition
        navigator.geolocation.getCurrentPosition(App.foundPosition);
    },

    foundPosition: function (pos) {
        App.lat = pos.coords.latitude;
        App.lng = pos.coords.longitude;
        App.getAdress();
    },
    getAdress: function(){
        var requestUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + App.lat + "," + App.lng + "&key=" + App.KEY;

        window.jQuery.ajax({
            url: requestUrl,
            success: function (data) {
                var city = data.results[1].address_components[0].short_name;
                $('#location').val(city);

            }
        })
    }
};
App.init();