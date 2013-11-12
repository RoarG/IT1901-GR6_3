<?php
/*
 * File: stats.php
 * Holds: Displaying stats for the system
 * Last updated: 28.10.13
 * Project: Prosjekt1
 * 
*/

//
// Include and intiate base
//

require_once 'base.php';
$base = new Base();
$base->assign('script', 'stats.php');

if (!$base->userLoggedIn()) {
    // User is already logged in, he should be moved back to the home-screen
    $base->sendRedirect('login.php');
}
else {
    // User has not tried to login already, display the template
    $base->assign('loggedIn', true);
    
    // Antall systemer
    $num_systems_all = "SELECT COUNT(id) as 'antall_sys' FROM system";
    $num_systems_all_query = $base->db->query($num_systems_all);
    $row1 = $num_systems_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_system', number_format($row1['antall_sys']));
    
    // Antall sauer
    $num_sheep_this = "SELECT COUNT(sheep.id) as 'antall_sauer' FROM sheep LEFT JOIN system_sheep ON sheep.id = system_sheep.sheep WHERE system_sheep.system = ".$_SESSION['sysid'];
    $num_sheep_this_query = $base->db->query($num_sheep_this);
    $row2 = $num_sheep_this_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('local_sheep', number_format($row2['antall_sauer']));
    
    $num_sheep_all = "SELECT COUNT(id) as 'antall_sauer' FROM sheep";
    $num_sheep_all_query = $base->db->query($num_sheep_all);
    $row3 = $num_sheep_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_sheep', number_format($row3['antall_sauer']));
    
    // Antall levende sauer
    $num_alive_this = "SELECT COUNT(sheep.id) as 'antall_sauer' FROM sheep LEFT JOIN system_sheep ON sheep.id = system_sheep.sheep WHERE sheep.alive = 1 AND system_sheep.system = ".$_SESSION['sysid'];
    $num_alive_this_query = $base->db->query($num_alive_this);
    $row4 = $num_alive_this_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('local_alive', number_format($row2['antall_sauer']));
    
    $num_alive_all = "SELECT COUNT(id) as 'antall_sauer' FROM sheep WHERE alive = 1";
    $num_alive_all_query = $base->db->query($num_alive_all);
    $row5 = $num_alive_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_alive', number_format($row3['antall_sauer']));
    
    // Antall døde sauer
    $base->assign('local_dead', number_format($row2['antall_sauer'] - $row4['antall_sauer']));
    $base->assign('all_dead', number_format($row3['antall_sauer'] - $row5['antall_sauer']));
    
    // Antall brukere
    $num_users_this = "SELECT COUNT(user.id) as 'antall_brukere' FROM user LEFT JOIN system_user ON user.id = system_user.user WHERE system_user.system = ".$_SESSION['sysid'];
    $num_users_this_query = $base->db->query($num_users_this);
    $row6 = $num_users_this_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('local_user', number_format($row6['antall_brukere']));
    
    $num_sheep_all = "SELECT COUNT(id) as 'antall_brukere' FROM user";
    $num_sheep_all_query = $base->db->query($num_sheep_all);
    $row7 = $num_sheep_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_user', number_format($row7['antall_brukere']));
    
    // Antall notifications
    $num_notifications_this = "SELECT COUNT(id) as 'antall_notifications' FROM notification WHERE system = ".$_SESSION['sysid'];
    $num_notifications_this_query = $base->db->query($num_notifications_this);
    $row8 = $num_notifications_this_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('local_notifications', number_format($row8['antall_notifications']));
    
    $num_notifications_all = "SELECT COUNT(id) as 'antall_notifications' FROM notification";
    $num_notifications_all_query = $base->db->query($num_notifications_all);
    $row9 = $num_notifications_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_notifications', number_format($row9['antall_notifications']));
    
    // Antall uleste notifications
    $num_unread_this = "SELECT COUNT(id) as 'antall_notifications' FROM notification WHERE is_read = '0' AND system = ".$_SESSION['sysid'];
    $num_unread_this_query = $base->db->query($num_unread_this);
    $row9 = $num_unread_this_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('local_unread', number_format($row9['antall_notifications']));
    
    $num_unread_all = "SELECT COUNT(id) as 'antall_notifications' FROM notification WHERE is_read = '0'";
    $num_unread_all_query = $base->db->query($num_unread_all);
    $row10 = $num_unread_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_unread', number_format($row10['antall_notifications']));
    
    // Antall logginnlegg
    $num_log_this = "SELECT COUNT(id) as 'antall_log' FROM log WHERE system = ".$_SESSION['sysid'];
    $num_log_this_query = $base->db->query($num_log_this);
    $row11 = $num_log_this_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('local_log', number_format($row11['antall_log']));
    
    $num_log_all = "SELECT COUNT(id) as 'antall_log' FROM log";
    $num_log_all_query = $base->db->query($num_log_all);
    $row12 = $num_log_all_query->fetch(PDO::FETCH_ASSOC);
    $base->assign('all_log', number_format($row12['antall_log']));
    
    // Display the map
    $base->display('stats.tpl');
}