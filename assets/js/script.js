//
// Different methods we need
//

function is_numeric(strString) { // http://www.pbdr.com/vbtips/asp/JavaNumberValid.htm (modified)
    var strValidChars = '0123456789';
    var strChar;
    var blnResult = true;

    if (strString.length == 0) {
        return false;
    }
    
    for (i = 0; i < strString.length && blnResult == true; i++) {
        strChar = strString.charAt(i);
        if (strValidChars.indexOf(strChar) == -1) {
            blnResult = false;
        }
    }
    
    return blnResult;
}

//
// jQuery goes here
//

$(document).ready(function () {

    //
    // Variables we need
    //
    
    var map = null;
    var map_objects = {'marker': [], 'infowindow': []};
    var map_num_to_id = {};
    var map_num_to_id_num = 0;
    var months = ['Jan','Feb','Mar','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Des'];
    var sim_on = false;
    var sim_time = new Date().getTime();
    var sim_speed = 20;
    var sim_interval = null;
    var sim_update_time = 100;
    var sim_update_progress = 0;
    var sim_objects_current = 0;
    var sim_objects_list = [];
    
    //
    // Everything related to maps
    //
    
    
    if ($('#map').length > 0) {
        // We have a map!
        
        // Slider for speed
        $('#speed').slider({
            min: 1,
            max: 350,
            value: 20,
            slide: function( event, ui ) {
                // Update value
                sim_speed = ui.value;
                
                // Update visible text
                $('#speed_val').html(ui.value+'x');
                
                // Calculate
                calculate_one_min_eq();
            }
        });
        
        // Dragable
        $('#map_controlls').draggable();

        // Method for calulcating what one minute in the simulator equals
        function calculate_one_min_eq() {
            var one_min_time = 60*sim_speed;
            var one_min_hours = Math.floor(one_min_time / 3600);
            one_min_time -= one_min_hours*3600;
            var one_min_minutes = Math.floor(one_min_time / 60);
            one_min_time -= one_min_minutes*60;
            $('#sim_one_min_eq').html(one_min_hours + ' timer og '+ one_min_minutes+ ' minutter');
        }
        
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
        
        var sheep_alive_num = 0;
        
        var sheep_json = jQuery.parseJSON($('#sheep_json').html());
        
        // Get all the sheeps and display them
        for (var i = 0; i < sheep_json.length; i++) {
            // Reference to current sheep
            var current_sheep = sheep_json[i];
            
            // Only animate not dead-sheeps (of course
            if (current_sheep.alive == '1') {
                // Update how many objects we hare working with
                sim_objects_list.push(i);
                sheep_alive_num++;
            }
            
            // Setting the correct id
            map_num_to_id['sheep_'+i] = current_sheep.chip;
            map_num_to_id_num++;
            
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
                content: '<div class="map-overlay"><h2>' + current_sheep.name+' (#'+current_sheep.identification+')'+'</h2><p><b>Status:</b> '+((current_sheep.alive == '1')?'Lever':'Død')+'</p><p><b>Posisjon:</b> ['+current_sheep.lat+', '+current_sheep.lng+']</p><p><b>Siste oppdatering:</b> '+last_updated_pretty+'</p> '+((current_sheep.alive == '1')?'<input type="button" class="push_right" data-type="attack" data-num="'+i+'" value="Angrip" data-chip="'+current_sheep.chip+'"/> <input class="red" type="button" data-type="kill" value="Drep" data-num="'+i+'" data-chip="'+current_sheep.chip+'"/>':'')+'</div>'
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
            
            // All dragable-stuff goes here
            map_objects.marker[i].setDraggable(true);
            
            // The eventListener
            google.maps.event.addListener(map_objects.marker[i], 'dragend', function(key) {
                return function() {
                    var point = map_objects.marker[key].getPosition();
                    
                    $.ajax ({
                        url: 'ajax_drag.php',
                        cache: false,
                        headers: { 'cache-control': 'no-cache' },
                        dataType: 'json',
                        type: 'post',
                        data: {id :  map_num_to_id['sheep_'+key], 'num' : key, 'lat': point.lat(), 'lng' : point.lng()},
                        success: function(json) {                    
                            if (json.state == 'ok') {
                                // Store reference
                                var i = json.num;
                                
                                // Update position
                                map_objects.marker[i].setPosition(new google.maps.LatLng(json.response.response.lat, json.response.response.lng));
                                
                                // Konverterer siste oppdatering
                                var last_updated = json.response.response.last_updated.split(' ');
                                var last_updated_date = last_updated[0].split('-');
                                var last_updated_pretty = parseInt(last_updated_date[2])+'. '+months[parseInt(last_updated_date[1])-1]+' '+last_updated_date[0]+', kl: '+last_updated[1];
                                
                                // Update text
                                map_objects.infowindow[i].setContent('<div class="map-overlay"><h2>' + json.response.response.name+' (#'+json.response.response.identification+')'+'</h2><p><b>Status:</b> '+((json.response.response.alive == '1')?'Lever':'Død')+'</p><p><b>Posisjon:</b> ['+json.response.response.lat+', '+json.response.response.lng+']</p><p><b>Siste oppdatering:</b> '+last_updated_pretty+'</p>'+((json.response.response.alive == '1')?'<input type="button" class="push_right" data-type="attack" value="Angrip" data-num="'+i+'" data-id="'+json.response.response.id+'"/> <input class="red" type="button" data-type="kill" value="Drep" data-num="'+i+'" data-id="'+json.response.response.id+'"/>':'')+'</div>');
                            }
                        }
                    });
                }
            }(i));
        }
        
        // Update how often we should do some simulating
        sim_update_time = (24*60*60*1000)/(sheep_alive_num*3);
        
        $('#sim_toggle').on('click', function () {
            
            if (sim_on) {
                $(this).removeClass('off').val('Skru på');
                sim_on = false;
                clearInterval(sim_interval);
            }
            else {
                $(this).addClass('off').val('Skru av');
                sim_on = true;
                sim_interval = setInterval(simulate, 100);
            }
        });
        
        $('#map').on('click', '.map-overlay input', function () {
            var this_chip = $(this).data('chip');
            var action_type = 'killed';
            if (!$(this).hasClass('red')) {
                action_type = 'wounded';
            }
            
            // Remove killed object so it is not randomly moved anymore
            if (action_type == 'killed') {
                var this_id = $(this).data('chip');
                for (var i = 0; i < map_num_to_id_num; i++) {
                    if (this_id == map_num_to_id['sheep_'+i]) {
                        sim_objects_list.pop(i);
                    }
                }
            }
            
            $.ajax ({
                url: 'ajax_action.php',
                cache: false,
                headers: { 'cache-control': 'no-cache' },
                dataType: 'json',
                type: 'post',
                data: {id :  $(this).data('chip'), 'type': action_type, num: $(this).data('num') },
                success: function(json) {               
                    if (json.state == 'ok') {
                        // Store reference
                        var i = json.num;
                        
                        // Update position
                        map_objects.marker[i].setPosition(new google.maps.LatLng(json.response.response.lat, json.response.response.lng));
                        
                        // Konverterer siste oppdatering
                        var last_updated = json.response.response.last_updated.split(' ');
                        var last_updated_date = last_updated[0].split('-');
                        var last_updated_pretty = parseInt(last_updated_date[2])+'. '+months[parseInt(last_updated_date[1])-1]+' '+last_updated_date[0]+', kl: '+last_updated[1];
                        
                        // Update text
                        map_objects.infowindow[i].setContent('<div class="map-overlay"><h2>' + json.response.response.name+' (#'+json.response.response.identification+')'+'</h2><p><b>Status:</b> '+((json.response.response.alive == '1')?'Lever':'Død')+'</p><p><b>Posisjon:</b> ['+json.response.response.lat+', '+json.response.response.lng+']</p><p><b>Siste oppdatering:</b> '+last_updated_pretty+'</p>'+((json.response.response.alive == '1')?'<input type="button" class="push_right" data-type="attack" value="'+((this_chip == json.response.response.chip)?'Angrepet':'Angrip')+'" data-num="'+i+'" data-chip="'+json.response.response.chip+'"/> <input class="red" type="button" data-type="kill" data-num="'+i+'" value="Drep" data-chip="'+json.response.response.chip+'"/>':'')+'</div>');
                        
                        // Update marker-image
                        var marker_image = 'marker_blue.png';
                        if (json.response.response.alive == '0') {
                            marker_image = 'marker_red.png';
                        }
                        
                        map_objects.marker[i].setIcon({
                            url: 'assets/css/gfx/markers/'+marker_image,
                            size: new google.maps.Size(72, 72),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(37, 37)});
                    }
                }
            });
        });
        
        // Calculate initial
        calculate_one_min_eq();
        
        // Initial clock
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();
        $('#sim_clock').html(((hours < 10)?'0':'')+hours + ':' + ((minutes < 10)?'0':'')+minutes + ':' + ((seconds < 10)?'0':'')+seconds);
        
    }
    
    //
    // Simulator
    //
    
    function simulate() {
        // Calculate new time
        var sim_time_diff = (1000*sim_speed);
        var sim_time_now = sim_time + sim_time_diff;
        
        // Display clock
        var sim_time_now_date = new Date(sim_time_now);
        var hours = sim_time_now_date.getHours();
        var minutes = sim_time_now_date.getMinutes();
        var seconds = sim_time_now_date.getSeconds();
        $('#sim_clock').html(((hours < 10)?'0':'')+hours + ':' + ((minutes < 10)?'0':'')+minutes + ':' + ((seconds < 10)?'0':'')+seconds);
        
        // Update the actual time
        sim_time = sim_time_now;
        
        // Check the progress
        sim_update_progress += sim_time_diff;
        
        if (sim_update_progress >= sim_update_time) {
            // Simulate sheep!
            sim_update_progress = 0;
            
            $.ajax ({
                url: 'ajax_sim.php',
                cache: false,
                headers: { 'cache-control': 'no-cache' },
                dataType: 'json',
                type: 'post',
                data: {id :  map_num_to_id['sheep_'+sim_objects_list[sim_objects_current]], 'num' : sim_objects_list[sim_objects_current]},
                success: function(json) {                    
                    if (json.state == 'ok') {
                        // Store reference
                        var i = json.num;
                        
                        // Update position
                        map_objects.marker[i].setPosition(new google.maps.LatLng(json.response.response.lat, json.response.response.lng));
                        
                        // Konverterer siste oppdatering
                        var last_updated = json.response.response.last_updated.split(' ');
                        var last_updated_date = last_updated[0].split('-');
                        var last_updated_pretty = parseInt(last_updated_date[2])+'. '+months[parseInt(last_updated_date[1])-1]+' '+last_updated_date[0]+', kl: '+last_updated[1];
                        
                        // Update text
                        map_objects.infowindow[i].setContent('<div class="map-overlay"><h2>' + json.response.response.name+' (#'+json.response.response.identification+')'+'</h2><p><b>Status:</b> '+((json.response.response.alive == '1')?'Lever':'Død')+'</p><p><b>Posisjon:</b> ['+json.response.response.lat+', '+json.response.response.lng+']</p><p><b>Siste oppdatering:</b> '+last_updated_pretty+'</p>'+((json.response.response.alive == '1')?'<input type="button" class="push_right" data-type="attack" data-num="'+i+'" value="Angrip" data-chip="'+json.response.response.chip+'"/> <input class="red" type="button" data-type="kill" value="Drep" data-num="'+i+'" data-chip="'+json.response.response.chip+'"/>':'')+'</div>');
                        
                        // Update marker-image
                        var marker_image = 'marker_blue.png';
                        if (json.response.response.alive == '0') {
                            marker_image = 'marker_red.png';
                        }
                        
                        map_objects.marker[i].setIcon({
                            url: 'assets/css/gfx/markers/'+marker_image,
                            size: new google.maps.Size(72, 72),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(37, 37)});
                    }
                }
            });
            
            // Figure out what should be simulated the next time
            if (sim_objects_current == (sim_objects_list.length - 1)) {
                sim_objects_current = 0;
            }
            else {
                sim_objects_current += 1;
            }
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