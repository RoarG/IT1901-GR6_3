<?php
/*
 * File: logout.php
 * Holds: The file for logging out of the simulator
 * Last updated: 28.10.13
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
    // User is not logged in, redirect to login
    $base->sendRedirect('login.php');
}
else {
    // Log user out
    $base->userLogout();
    
    // Redirect
    $base->sendRedirect('login.php');
}
?>