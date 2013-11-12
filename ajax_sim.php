<?php
/*
 * File: ajax_sim.php
 * Holds: Simulate the movement for the sheeps at interval
 * Last updated: 23.10.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();
$base->assign('script', 'ajax_drag.php');

//
// Function for generating center of the map
//

function find_center($arr) { // http://stackoverflow.com/a/14231286/921563
    if (count($arr) == 0) {
        return array('lat' => 60,
                     'lng' => 10);
    }
    
    // Define all variables
    $x = 0;
    $y = 0;
    $z = 0;
    
    foreach ($arr as $v) {
        // To degrees
        $lat = $v['lat'] * (M_PI / 180);
        $lng = $v['lng'] * (M_PI / 180);
        
        // Adding the numbers
        $x += cos($lat) * cos($lng);
        $y += cos($lat) * sin($lng);
        $z += sin($lat);
    }
    
    // Finding average
    $x = $x/count($arr);
    $y = $y/count($arr);
    $z = $z/count($arr);
    
    // Calculating back to coordinates
    $lo = atan2($y, $x);
    $hy = sqrt($x * $x + $y * $y);
    $la = atan2($z, $hy);
    
    // Returning everything
    return array('lat' => (string)($la * (180 / M_PI)), 
                 'lng' => (string)($lo * (180 / M_PI)));
}

//
//
//

function distance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return ($miles * 1.609344)*1000;
}

//
//
//

function move($lat, $lng, $distance, $angle) { // Modified from http://stackoverflow.com/a/18982580/921563
    $earthMeanRadius = 6371009.0; // metres

    $dest_lat = rad2deg(
        asin(
            sin(deg2rad($lat)) *
            cos($distance / $earthMeanRadius) +
            cos(deg2rad($lat)) *
            sin($distance / $earthMeanRadius) *
            cos(deg2rad($angle))
        )
    );
    
    $dest_lng = rad2deg(
        deg2rad($lng) +
        atan2(
            sin(deg2rad($angle)) *
            sin($distance / $earthMeanRadius) *
            cos(deg2rad($lat)),
            cos($distance / $earthMeanRadius) -
            sin(deg2rad($lat)) * sin(deg2rad($dest_lat))
        )
    );
    
    return array('lat' => $dest_lat, 'lng' => $dest_lng);
}

//
// json-content
//

$ret = array();

//
// Check if logged in or not
//


if ($base->userLoggedIn()) {
    $current = null;
    // Getting all sheeps with positions for the current system
    $get_all_position = "SELECT sh.id, sh.identification, sh.lat, sh.lng, sh.alive, sh.name, sh.last_updated
    FROM sheep sh 
    LEFT JOIN system_sheep AS sh_sys ON sh_sys.sheep = sh.id
    WHERE sh_sys.system = :system
    ORDER BY sh.id ASC";
    
    $get_all_position_query = $base->db->prepare($get_all_position);
    $get_all_position_query->execute(array(':system' => $_SESSION['sysid']));
    while ($row = $get_all_position_query->fetch(PDO::FETCH_ASSOC)) {
        // Adding the row to the array
        $sheeps[] = $row;
        
        // Check if this sheep is the current one
        if ($row['id'] == $_POST['id']) {
            $current = $row;
        }
    }
    // Check if we got something
    if (isset($current['id'])) {
        // Calculate the center of the map
        $center = find_center($sheeps);
        
        // Find the distance from the center
        $distance_from_center = distance($current['lat'], $current['lng'], $center['lat'], $center['lng']);
        
        // Calculate how far the sheep should be allowed to move
        $movement = rand(0, 400-$distance_from_center);
        
        // Calculate new positions
        $angle = rand(0, 360);
        $new_pos = move($current['lat'], $current['lng'], $movement, $angle);
        
        // Find the new distance from the center
        $enw_distance_from_center = distance($new_pos['lat'], $new_pos['lng'], $center['lat'], $center['lng']);
        
        // Get the api-url
        $api_url = str_replace('simulator/ajax_sim.php', '', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'app/api';
        
        // Build post-string
        $post_string = 'type=position&lat='.$new_pos['lat'].'&lng='.$new_pos['lng'];
        
        // Do the cURL
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $api_url.'/chip/'.$_POST['id'].'/?method=put&sheep_token='.$base->getSheepToken());
        curl_setopt($ch,CURLOPT_POST, 3);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret['response'] = json_decode(curl_exec($ch));
        curl_close($ch);
        
        $ret['state'] = 'ok';
        $ret['num'] = $_POST['num'];
    }
    else {
        $ret['state'] = 'error';
    }
}
else {
    $ret['state'] = 'error';
}

echo json_encode($ret);
?>