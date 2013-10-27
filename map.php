<?php
/*
 * File: map.php
 * Holds: Displaying the map happens here
 * Last updated: 27.10.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();
$base->assign('script', 'map.php');

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
// Check if logged in or not
//

if (!$base->userLoggedIn()) {
    // User is already logged in, he should be moved back to the home-screen
    $base->sendRedirect('index.php');
}
else {
    // User has not tried to login already, display the template
    $base->assign('loggedIn', true);
    
    // Fetch the initial sheeps
    $sheeps = array();
    
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
    }

    // Assign it
    $base->assign('sheep_json',json_encode($sheeps));
    $base->assign('map_json',json_encode(find_center($sheeps)));
    
    // Display the map
    $base->display('map.tpl');
}
?>