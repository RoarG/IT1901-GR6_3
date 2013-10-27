<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- Etc START -->
    <base href="" />
    <title>Sheep Locator :: Simulator</title>
    <!-- Etc END -->
    
    <!-- Meta START -->
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=1024" name="viewport" />
    <!-- Meta END -->
    
    <!-- Styling START -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- Styling END -->
    
    <!-- Libraries START -->
    <script type="text/javascript" src="assets/js/lib/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="assets/js/lib/jquery-ui-1.10.3.min.js"></script>
    <script type="text/javascript" src="assets/js/lib/jquery.uniform.min.js"></script>
    <!-- Libraries END -->
    
    <!-- Google Libs START -->
    <script type="text/javascript" src="http://www.google.com/jsapi"></script> 
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=geometry&amp;key=AIzaSyCQFPTSj0WHj_zgEmLxbQk3METKu5q8bRA&amp;sensor=false&amp;language=no"></script>
    <!-- Google Libs END -->
    
    <!-- System START -->
    <script type="text/javascript" src="assets/js/script.js"></script>
    <!-- System END -->
</head>
<body>
<input type="hidden" value="[[+$hash]]" name="hash" id="hash" />
<div id="header">
    <div id="inner_header">
        <div id="toplogo">
            <img width="170" height="39" alt="Sheep Locator" src="assets/css/gfx/sheep_locator_simlogo.png" />
        </div>
        <ul>
            <li[[+If $script == 'index.php']] class="active"[[+/If]]><a href="index.php">Hjem</a></li>
            <li[[+If $script == 'map.php']] class="active"[[+/If]]><a href="map.php">Kart</a></li>
            <li[[+If $script == 'stats.php']] class="active"[[+/If]]><a href="stats.php">Stats</a></li>
            <li[[+If $script == 'login.php']] class="active"[[+/If]]>[[+If $loggedIn]]<a href="logout.php">Logg ut[[+else]]<a href="login.php">Logg inn[[+/If]]</a></li>
            <li class="system_holder_text [[+If $loggedIn]]on[[+else]]off[[+/If]]"><span>System:</span></li>
            <li class="system_holder [[+If $loggedIn]]on[[+else]]off[[+/If]]"><div id="chose_system_outer"><div id="chose_system"><form action="" method="post" id="system_form" name="system_form">[[+$systems]]</form></div></div></li>
        </ul>
    </div>
</div>