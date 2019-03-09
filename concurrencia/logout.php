<?php 
	
	session_start();
	require "conexion.php";
	require "session_control_functions.php";
	
	$session_token = session_id();
	revokeSession($session_token);

	session_destroy();
	$_SESSION = array();

 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>LogOut</title>
 </head>
 <body>
 	<h1>SESSION CERRADA</h1>
 </body>
 </html>