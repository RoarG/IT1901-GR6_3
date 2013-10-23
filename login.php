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

//
// Check if logged in or not
//

if ($base->userLoggedIn()) {
    // User is already logged in, he should be moved back to the home-screen
    $base->sendRedirect('index.php');
}
else {
    // User is not logged in, display
    if (isset($_POST['login'])) {
        // Check if the password entered was correct
        if ($_POST['master_pw'] == MASTER_PASSWORD) {
            // The password was correct, log the user in
            $base->userLogin();
            
            // Send him/her to the frontscreen
            $base->sendRedirect('index.php');
        }
        else {
            // The password was incorrect
            $base->assign('error', true);
            
            // Display the template
            $base->display('login.tpl');
        }
    }
    else {
        // User has not tried to login already, display the template
        $base->display('login.tpl');
    }
}
?>