$(document).ready(function () {
    //
    //
    //
    
    var map = null;
    var map_objects = {'marker': [], 'infowindow': []};
    var months = ['Jan','Feb','Mar','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Des'];
    
    //
    // Everything related to maps
    //
    
    if ($('#map').length > 0) {
        // We have a map!
        
        // Set map-height
        $('#map').css('height', ($(window).innerHeight() - 86));
        
        // Get the center
        var map_json = jQuery.parseJSON($('#map_json').html());
        
        // Init the map itself
        map = new google.maps.Map(document.getElementById("map"),{
            center: new google.maps.LatLng(map_json.lat, map_json.lng), 
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.SATELLITE,
            streetViewControl: false});
        
        // Empty map-objects
        map_objects.marker = [];
        map_objects.infowindow = [];
        
        var sheep_json = jQuery.parseJSON($('#sheep_json').html());
        
        // Get all the sheeps and display them
        for (var i = 0; i < sheep_json.length; i++) {
            // Reference to current sheep
            var current_sheep = sheep_json[i];
            
            // Defining the color of the marker
            var marker_image = 'marker_blue.png';
            
            if (current_sheep.alive == '0') {
                // This sheep is dead!
                marker_image = 'marker_red.png';
            }
            
            var map_marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(current_sheep.lat, current_sheep.lng),
                icon: {
                    url: 'assets/css/gfx/markers/'+marker_image,
                    size: new google.maps.Size(72, 72),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(37, 37)},
                visible: true,
                title: current_sheep.name+' (#'+current_sheep.identification+')'
            });
            
            // Add marker to the array
            map_objects.marker.push(map_marker);
            
            // Konverterer siste oppdatering
            var last_updated = current_sheep.last_updated.split(' ');
            var last_updated_date = last_updated[0].split('-');
            var last_updated_pretty = parseInt(last_updated_date[2])+'. '+months[parseInt(last_updated_date[1])-1]+' '+last_updated_date[0]+', kl: '+last_updated[1];
            
            // Generate infowindow content and eventListener
            var temp_infowindow = new google.maps.InfoWindow({
                content: '<div class="map-overlay"><h2>' + current_sheep.name+' (#'+current_sheep.identification+')'+'</h2><p><b>Status:</b> '+((current_sheep.alive == '1')?'Lever':'Død')+'</p><p><b>Posisjon:</b> ['+current_sheep.lat+', '+current_sheep.lng+']</p><p><b>Siste oppdatering:</b> '+last_updated_pretty+'</p> <input type="button" value="Vis info" data-id="'+current_sheep.id+'"/></div>'
            });
            
            // Add infowindow to the array
            map_objects.infowindow.push(temp_infowindow);
            
            google.maps.event.addListener(map_objects.marker[i], 'click', function(key) {
                return function() {
                    for (var j = 0; j < map_objects.infowindow.length; j++) {
                        map_objects.infowindow[j].close();
                    }
                    map_objects.infowindow[key].open(map, map_objects.marker[key]);
                }
            }(i));
        }
    }
    
    //
    // Chose system
    //
    
    if ($('#chose_system_outer').parent().hasClass('on')) {
        $('#systems_select').uniform({
            selectClass: 'selectFront',
            selectAutoWidth: false
        });
        
        $('#systems_select').on('change', function () {
            $('#system_form').submit();
        });
    }
});