<?php
/*
 * File: index.php
 * Holds: The main-file for the simulator-page
 * Last updated: 23.10.13
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
// Check if logged in or not
//

if ($base->userLoggedIn()) {
    $base->assign('loggedIn', true);
    $base->display('index.tpl');
}
else {
    $base->sendRedirect('login.php');
}
?>