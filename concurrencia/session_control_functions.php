<?php 
	
	// session_start();

	// REVOCAR LICENCIAS
	function revokeLic(){
		global $con;
		$now = date('Y-m-d H:i:s');
		$con->query("UPDATE licencia SET expirada = 1 WHERE fecha_expiracion <= '$now'" );
	}
	// REVOCAR SESIONES EXPIRADAS
	function revokeSessions(){
		global $con;
		$now = date('Y-m-d H:i:s');
		$con->query("UPDATE session_control SET expirado = 1 WHERE expiracion <= '$now'" );
	}
	function revokeSession($session_id){
		global $con;
		// $now = date('Y-m-d H:i:s');
		$con->query("UPDATE session_control SET expirado = 1 WHERE token = '$session_id'" );
	}
	// REVOCAR SESIONES Y LICENCIAS
	function revoke(){
		revokeSessions();
		revokeLic();
	}

	// REGISTRAR NUEVA SESION
	function registrarSession($session_id){
		global $con, $session_control_config;
		
		$now = date('Y-m-d H:i:s');
		$expiration_time = $session_control_config['expiration_time'];
		$expire = date("Y-m-d H:i:s", (time() + $expiration_time));
		$maquina = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		
		$sql = "INSERT INTO session_control 
		(token, creacion, actualizacion, duracion, expiracion, expirado, maquina, user_agent) 
		VALUES ('$session_id', '$now', '$now', $expiration_time, '$expire', 0, '$maquina', '$agent')";
		
		return $con->query($sql);
	}

	// VERIFICAR SI UNA SESSION EXISTE
	function getSession($session_id){
		global $con;
		$res = asociativo( $con->query("SELECT * FROM session_control WHERE token = '$session_id'") );
		if( count($res) > 0 ){
			return $res[0];
		}
		return null;
	}

	// ACTUALIZAR SESSION
	function updateSession($session_id){
		global $con, $session_control_config;

		$now = date("Y-m-d H:i:s");
		$expiration_time = $session_control_config['expiration_time'];
		$expire = date("Y-m-d H:i:s", (time() + $expiration_time));

		$maquina = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];

		$sql = "UPDATE session_control SET 
					actualizacion = '$now', 
					duracion = $expiration_time, 
					expiracion = '$expire', 
					expirado = 0, 
					maquina = '$maquina', 
					user_agent = '$agent' 
				WHERE token = '$session_id'";

		return $con->query($sql);
	}

	// verifica si una sesion esta abierta
	function sessionIsOpen($session){
		return !($session['expirado'] == 1);
	}

	// obtener sesiones abiertas
	function getOpenSessions(){
		global $con;
		return asociativo($con->query("SELECT * FROM session_control WHERE expirado = 0"));
	}
	// obtener nuemero de licencias abiertas
	function getOpenSessionsNumber(){
		$sesions = getOpenSessions();
		return count($sesions);
	}

	// obtener licencias activas
	function getActiveLicences(){
		global $con;
		return asociativo($con->query("SELECT * FROM licencia WHERE expirada = 0"));
	}
	// obtener cantidad de licencias activas
	function getActiveLicencesNumber(){
		$lic = getActiveLicences();
		$num = 0;
		foreach ($lic as $key => $l) {
			$num += $l['cantidad'];
		}
		return $num;
	}
	// permitir la entrada de una session
	function allowSession($session){
		if( $session ){ updateSession( $session['token']); }
		else { registrarSession( session_id() ); }
	}

	// controla la entrada de una nueva session
	function session_control(){
		global $session_control_config;
		// revocar sesiones expiradas y licencias expiradas
		revoke();
		
		$session_token = session_id();
		$numero_lic = getActiveLicencesNumber();
		$open_sessions = getOpenSessionsNumber();
		$session_exist = getSession($session_token);

		if( $numero_lic > 0 && (
			($open_sessions < $numero_lic) || ($session_exist && sessionIsOpen($session_exist) ))){
			allowSession($session_exist);
		}
		else {
			header("Location: {$session_control_config['template_licences_exceded']}?num_lic={$numero_lic}&time={$session_control_config['expiration_time']}");
			if( $session_control_config['logout_on_exceded'] ){
				session_control_logout( $session_control_config['destroy_session_on_logout'] );
			}
			exit();
		}
	}

	function session_control_logout($destroy_session = false){
		revokeSession( session_id() );
		if( $destroy_session ){
			session_destroy();
			$_SESSION = array();
		}
	}

 ?>