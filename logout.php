<?php
/*
 * File: login.php
 * Holds: The file for logging into the simulator
 * Last updated: 23.10.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();
$base->assign('script', 'logout.php');
$base->assign('loggedIn', false);

//
// Check if logged in or not
//

if (!$base->userLoggedIn()) {
    // User is already logged in, he should be moved back to the home-screen
    $base->sendRedirect('index.php');
}
else {
    // Log user out
    $base->userLogout();
    
    // Redirect
    $base->sendRedirect('login.php');
}
?>