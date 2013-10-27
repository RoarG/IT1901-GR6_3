<?php
/*
 * File: base.php
 * Holds: Holds the system-information
 * Last updated: 27.10.13
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
    
    public $db; // Holds the database-connection
    private $smarty; // Holds the smarty-library
    private $base_password = null; // Holds the master-password (cached)
    
    //
    //  Constructor
    //
    
    public function __construct() {
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
        
        // Set hash
        $this->smarty->assign('hash', ((isset($_SESSION['hash']))?$_SESSION['hash']:null));
        
        // Check if systemchange was submitted
        if (isset($_POST['systemchange_was_submitted']) and $_POST['systemchange_was_submitted'] == 1) {
            $this->changeSystem();
        }
    }
    
    //
    // Checking if the user is logged in or not
    //
    
    public function userLoggedIn() {
        // Check if password is fetched, fetch if not
        if (isset($_SESSION['hash']) and $_SESSION['hash'] == $this->getMasterPassword()) {
            // User is logged in
            return true;
        }
        else {
            // User is not logged in
            return false;
        }
    }
    
    //
    // Log the user in
    //
    
    public function userLogin() {
        $_SESSION['hash'] = $this->getMasterPassword();
    }
    
    //
    // Send redirect to user
    //
    
    public function sendRedirect($dest) {
        // Sending user with header-location
        header('Location: '.$dest);
    }
    
    //
    // Assign value to smarty
    //
    
    public function assign ($key, $val) {
        $this->smarty->assign($key, $val);
    }
    
    //
    // Displaying a template using smarty
    //
    
    public function display($tpl) {
        $this->getSystemList();
        $this->smarty->display($tpl);
    }
    
    //
    // Fetch the master-password
    //
    
    public function getMasterPassword() {
        // Check if password is already sat
        if ($this->base_password != null) {
            // Already stored, just return
            return $this->base_password;
        }
        else {
            // Fetch from database
            $get_master = "SELECT pswd
            FROM master";
            
            $get_master_query = $this->db->query($get_master);
            $row = $get_master_query->fetch(PDO::FETCH_ASSOC);
            
            // Set for caching
            $this->base_password = $row['pswd'];
            
            // Return the password
            return $row['pswd'];
        }
    }
    
    //
    // Method that outputs all the systems available and selectes the current one
    //
    
    public function getSystemList() {
        $ret = '';
        
        // Only run the query if the user is already logged in
        if ($this->userLoggedIn()) {
            // Check if systemid is sat
            $has_sysid = false;
            if (isset($_SESSION['sysid'])) {
                $has_sysid = true;
            }
            
            $ret .= '<input type="hidden" name="systemchange_was_submitted" value="1" /><select name="systems_select" id="systems_select">';
            
            $get_all_systems = "SELECT id, name, sheep_token
            FROM system
            ORDER BY name ASC";
            
            $get_all_systems_query = $this->db->prepare($get_all_systems);
            $get_all_systems_query->execute();
            while ($row = $get_all_systems_query->fetch(PDO::FETCH_ASSOC)) {
                if (!$has_sysid) {
                    $_SESSION['sysid'] = $row['id'];
                    $has_sysid = true;
                }
                
                $ret .= '<option '.(($_SESSION['sysid'] == $row['id'])?'selected="selected"':'').' value="'.$row['id'].'">'.$row['name'].'</option>';
            }
            
            $ret .= '</select>';
        }
        
        $this->smarty->assign('systems', $ret);
    }
    
    //
    // Method that updates the current systemid
    //
    
    private function changeSystem() {
        // Update sysid
        $_SESSION['sysid'] = $_POST['systems_select'];
        
        // Change location
        header("Location: map.php");
    }
    
    //
    // Return the sheep_token for the current system
    //
    
    public function getSheepToken() {
        // Fetch from database
        $get_sheep_token = "SELECT sheep_token
        FROM system
        WHERE id = :id";
        
        $get_sheep_token_query = $this->db->prepare($get_sheep_token);
        $get_sheep_token_query->execute(array(':id' => $_SESSION['sysid']));
        $row = $get_sheep_token_query->fetch(PDO::FETCH_ASSOC);
        
        return $row['sheep_token'];
    }
}
?>