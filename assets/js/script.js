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
    
    if ($('#chose_system_outer').parent().hasClass('on')) {
        $('#systems_select').uniform({
            selectClass: 'selectFront',
            selectAutoWidth: false
        });
    }
});