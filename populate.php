<?php
/*
 * File: populate.php
 * Holds: Adding 10.000 sheeps and 200 farmers/systems to the database
 * Last updated: 12.11.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();
$base->assign('script', 'index.php');

//
// Function for placing a sheep on the map based on a random coordinate from a center
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
// Let's go
//

if (isset($_GET['force']) and $_GET['force'] == 'true') {
    for ($i = 100; $i <= 300; $i++) {
        // Insert the sheep
        $new_system = "INSERT INTO system
        (name, contact, sheep_token)
        VALUES (:name, :contact, :sheep_token)";
                            
        $new_system_query = $base->db->prepare($new_system);
        $new_system_query->execute(array(':name' => 'Gård #'.$i, ':contact' => '[]', ':sheep_token' => md5($i).md5(time())));
                            
        // Get the system-id
        $system_id = $base->db->lastInsertId();
        
        // Insert the user
        $new_user = "INSERT INTO user
        (email, pswd, salt, access_token, name)
        VALUES (:email, :pswd, :salt, :access_token, :name)";
                            
        $new_user_query = $base->db->prepare($new_user);
        $new_user_query->execute(array(':email' => 'bonde'.$i.'@stud.ntnu.no', ':pswd' => 'bonde'.$i, ':salt' => md5($i).md5(time()), ':access_token' => '', ':name' => 'Bonde '.$i));
        
        // Get the user-id
        $user_id = $base->db->lastInsertId();
        
        // Insert system_user
        $new_system_user = "INSERT INTO system_user
        (system, user)
        VALUES (:system, :user)";
                            
        $new_system_user_query = $base->db->prepare($new_system_user);
        $new_system_user_query->execute(array(':system' => $system_id, ':user' => $user_id));
        
        for ($j = 1; $j <= 50; $j++) {
            // Calculate the position for this sheep
            $new_pos = move(60.337866705065, 10.461986893552, rand(0, 1000), rand(0, 360));
            
            // Insert sheep
            $new_sheep = "INSERT INTO sheep
            (identification, chip, name, birthday, weight, vaccine, lat, lng)
            VALUES (:identification, :chip, :name, :birthday, :weight, :vaccine, :lat, :lng)";
                           
            $new_sheep_query = $base->db->prepare($new_sheep);
            $new_sheep_query->execute(array(':identification' => '1000000'.$j, ':chip' => '1000000'.$j, ':name' => 'Sau #'.$i, ':birthday' => rand(2007, 2013).'-'.rand(1, 12).'-'.rand(0, 28), ':weight' => rand(10, 70), ':vaccine' => rand(0, 1), ':lat' => $new_pos['lat'], ':lng' => $new_pos['lng']));
            
            // Get the sheep-id
            $sheep_id = $base->db->lastInsertId();
            
            // Insert system_sheep
            $new_system_sheep = "INSERT INTO system_sheep
            (system, sheep)
            VALUES (:system, :sheep)";
            
            $new_system_sheep_query = $base->db->prepare($new_system_sheep);
            $new_system_sheep_query->execute(array(':system' => $system_id, ':sheep' => $sheep_id));
        }
    }                
}
else {
    echo '<p>For å kunne populere databasen må du legge til <b>?force=true</b> i adressen</p>';
}

?>