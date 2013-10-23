<?php
/*
 * File: base.php
 * Holds: Holds the system-information
 * Last updated: 23.10.13
 * Project: Prosjekt1
 * 
*/

//
// Debug
//

error_reporting(E_ALL);
ini_set('display_errors', '1');

//
// Timezone GMT+0
//

date_default_timezone_set('Europe/London');

//
// Set headers
//

header('Content-Type: text/html; charset=utf-8');

//
// Include the libraries we need
//

require_once 'lib/password_hash/password_hash.php';

//
// Trying to include local.php
//

if (file_exists(dirname(__FILE__).'/local.php')) {
    require_once 'local.php';
    
    // Check if master-password is over the required length
    if (strlen(MASTER_PASSWORD) < 10) {
        die('The master password must be at least 10 characters long. Edit it in your local.php-file.');
    }
}
else {
    die('You must copy the file local-example.php, rename it to local.php and include your database-information as well as master-password.');
}

//
// The base-class checking with sessions, login, databaseconnections etc
//

class Base {
    
    //
    //
    //
}
?>