$(document).ready(function () {
    //
    //
    //
    
    var map = null;
    
    //
    // Everything related to maps
    //
    
    if ($('#map').length > 0) {
        // We have a map!
        
        // Set map-height
        $('#map').css('height', ($(window).innerHeight() - 86));
        
        // Init the map itself
        map = new google.maps.Map(document.getElementById("map"),{
            center: new google.maps.LatLng(60, 10), 
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.SATELLITE,
            streetViewControl: false});
    }
    
    //
    // Chose system
    //
    
    if ($('#chose_system').hasClass('on')) {
        $.ajax ({
            url: '../app/api/master/list?master='+$('#hash').val(),
            cache: false,
            headers: { 'cache-control': 'no-cache' },
            dataType: 'json',
            success: function(json) {
                if (json.code == 200) {
                    //
                }
            }
        });
    }
});