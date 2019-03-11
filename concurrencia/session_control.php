<?php 
	// session_start();
	
	require "conexion.php";
	require "session_control_functions.php";

	$session_control_config = array(
		'expiration_time' => 60 * 3,
		'template_licences_exceded' => 'session_control_template.php',
		'logout_on_exceded' => false
	);

	// control de sesiones
	session_control();
 ?>