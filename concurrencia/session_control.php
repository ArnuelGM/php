<?php 
	session_start();
	require "conexion.php";
	require "session_control_functions.php";
	// revocar sesiones expiradas y licencias expiradas
	revoke();

	$session_token = session_id();
	$numero_lic = getActiveLicencesNumber();
	pre("lic: $numero_lic");
	$open_sessions = getOpenSessionsNumber();
	pre("open sessions: $open_sessions");

	$session_exist = getSession($session_token);

	if( $numero_lic > 0 && (
		($open_sessions < $numero_lic) || 
		($session_exist && sessionIsOpen($session_exist))
	)){
		allowSession($session_exist);
	}
	else {
		echo "número de licencias exedidas";
	}

	function allowSession($exist){
		$session_token = session_id();
		// registrar session;
		if( $exist ){
			updateSession($exist['token']);
			$res = "SESSION ACTUALIZADA";
		}
		else {
			registrarSession($session_token);
			$res = "SESSION CREADA";
		}
		pre($res);	
	}

 ?>