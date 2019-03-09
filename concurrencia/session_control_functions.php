<?php 
	
	// REVOCAR LICENCIAS
	function revokeLic(){
		global $con;
		// $now = date('Y-m-d H:i:s');
		$con->query("UPDATE licencia SET expirada = 1 WHERE fecha_expiracion <= NOW()" );
	}
	// REVOCAR SESIONES EXPIRADAS
	function revokeSessions(){
		global $con;
		// $now = date('Y-m-d H:i:s');
		$con->query("UPDATE session_control SET expirado = 1 WHERE expiracion <= NOW()" );
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
		global $con;
		$expiration_time = 60*3;
		$now = date('Y-m-d H:i:s');
		$expire = date("Y-m-d H:i:s", (time() + $expiration_time));
		$maquina = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$sql = "INSERT INTO session_control VALUES(NULL, '$session_id', NOW(), NOW(), $expiration_time, DATE_ADD(NOW(), INTERVAL $expiration_time SECOND), 0, '$maquina', '$agent')";
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
		global $con;
		$expiration_time = 60 * 3;
		$now = date("Y-m-d H:i:s");
		$expire = date("Y-m-d H:i:s", (time() + $expiration_time));
		$maquina = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$sql = "UPDATE session_control SET actualizacion = NOW(), duracion = $expiration_time, expiracion = DATE_ADD(NOW(), INTERVAL $expiration_time SECOND), expirado = 0, maquina = '$maquina', user_agent = '$agent' WHERE token = '$session_id'";
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

 ?>