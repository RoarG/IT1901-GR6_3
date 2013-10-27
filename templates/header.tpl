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
<div id="chose_system" class="[[+If $loggedIn]]on[[+else]]off[[+/If]]">[[+$systems]]</div>
<div id="header">
    <div id="inner_header">
        <ul>
            <li[[+If $script == 'index.php']] class="active"[[+/If]]><a href="index.php">Hjem</a></li>
            <li[[+If $script == 'map.php']] class="active"[[+/If]]><a href="map.php">Kart</a></li>
            <li[[+If $script == 'stats.php']] class="active"[[+/If]]><a href="stats.php">Stats</a></li>
            <li><a href="#">Logg ut</a></li>
        </ul>
    </div>
</div>