<?php
/*
 * File: map.php
 * Holds: Displaying the map happens here
 * Last updated: 23.10.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();

//
// Check if logged in or not
//

if (!$base->userLoggedIn()) {
    // User is already logged in, he should be moved back to the home-screen
    $base->sendRedirect('index.php');
}
else {
    // User has not tried to login already, display the template
    $base->display('map.tpl');
}
?>