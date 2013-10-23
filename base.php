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
require_once 'lib/smarty/Smarty.class.php';

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
    // Variables
    //
    
    private $db; // Holds the database-connection
    private $smarty; // Holds the smarty-library
    
    
    //
    //  Constructor
    //
    
    public function __construct () {
        // Starting session
        session_start();
        
        // Trying to connect to the database
        try {
            $this->db = new PDO("mysql:host=".DATABASE_HOST.";dbname=".DATABASE_TABLE, DATABASE_USER, DATABASE_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            $this->db = null;
        }

        // Authenticate if database-connection was successful
        if (!$this->db) {
            // Error goes here
        }
        
        // Init Smarty
        $this->smarty = $smarty = new Smarty();
    }
    
    //
    // Checking if the user is logged in or not
    //
    
    public function userLoggedIn () {
        if (isset($_SESSION['hash']) and $_SESSION['hash'] == MASTER_PASSWORD) {
            // User is logged in
            return true;
        }
        else {
            // User is not logged in
            return false;
        }
    }
    
    //
    // Send redirect to user
    //
    
    public function sendRedirect ($dest) {
        // Sending user with header-location
        header('Location: '.$dest);
    }
    
    //
    // Displaying a template using smarty
    //
    
    public function display ($tpl) {
        $this->smarty->display($tpl);
    }
}
?>