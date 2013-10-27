<?php
/*
 * File: ajax_action.php
 * Holds: Wound or kill a sheep here
 * Last updated: 23.10.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();
$base->assign('script', 'ajax_action.php');

//
// json-content
//

$ret = array();

//
// Check if logged in or not
//


if ($base->userLoggedIn()) {
    // Get the api-url
    $api_url = str_replace('simulator/ajax_action.php', '', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'app/api';
    
    // Build post-string
    $post_string = 'type='.$_POST['type'];
    
    // Do the cURL
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $api_url.'/chip/'.$_POST['id'].'/?method=put&sheep_token='.$base->getSheepToken());
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret['response'] = json_decode(curl_exec($ch));
    curl_close($ch);
    
    $ret['state'] = 'ok';
    $ret['num'] = $_POST['num'];
}
else {
    $ret['state'] = 'error';
}

echo json_encode($ret);
?>