<?php 
	session_start();
	class SessionControlManager {
		private $model = null;
		function __construct(){
			try {
				$this->model = new PDO(
					"sqlsrv:server={$_SESSION['host']};Database={$_SESSION['database']}",
					$_SESSION['cliente'],
					$_SESSION['clave'],
					array(
						//PDO::ATTR_PERSISTENT => true,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					)
				);
			} catch (Exception $e) {
				die("Error connecting to SQL Server: " . $e->getMessage());
			}
		}

		// controla la entrada de una nueva session
		function session_control(){
			global $session_control_config;
			// revocar sesiones expiradas y licencias expiradas
			$this->revoke();
			$session_token = session_id();
			$numero_lic = $this->getActiveLicencesNumber();
			$open_sessions = $this->getOpenSessions();
			$open_sessions_number = count($open_sessions);
			$session_exist = $this->getSession($session_token);
			if( $numero_lic > 0 && (
				($open_sessions_number < $numero_lic) || ($session_exist && $this->sessionIsOpen($session_exist) ))){
				$this->allowSession($session_exist);
			}
			else {
				header('Content-Type: text/html; charset=utf-8;');
				$tpl = $this->getTemplateForMaximumLicences();
				$tpl = $this->setLicencesToTemplate($tpl, $numero_lic);
				$tpl = $this->setNextSessionExpireTimeToTemplate($tpl, $open_sessions, $numero_lic);
				print_r($tpl);
				exit();
			}
		}

		// REVOCAR SESIONES Y LICENCIAS
		private function revoke(){
			$this->revokeSessions();
			$this->revokeLic();
		}

		// REVOCAR SESIONES EXPIRADAS
		private function revokeSessions(){
			$now = date('Y-m-d H:i:s');
			$this->model->exec("UPDATE session_control SET expirada = 1 WHERE expiracion <= '$now'" );
		}

		// REVOCAR LICENCIAS
		private function revokeLic(){
			$now = date('Y-m-d H:i:s');
			$this->model->exec("UPDATE licencia SET expirada = 1 WHERE expiracion <= '$now'" );
		}

		// REVOCA/EXPIRA UNA SESION
		private function revokeSession($session_id){
			$this->model->exec("UPDATE session_control SET expirada = 1 WHERE token = '$session_id'" );
		}

		// REGISTRAR NUEVA SESION
		private function registrarSession($session_id){
			global $session_control_config;
			$now = date('Y-m-d H:i:s');
			$expiration_time = $session_control_config['expiration_time'];
			$expire = date("Y-m-d H:i:s", (time() + $expiration_time));
			$maquina = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
			$agent = $_SERVER['HTTP_USER_AGENT'];

			$sql = "INSERT INTO session_control 
			(token, creacion, actualizacion, duracion, expiracion, expirada, maquina, user_agent) 
			VALUES ('$session_id', '$now', '$now', $expiration_time, '$expire', 0, '$maquina', '$agent')";

			return $this->model->exec($sql);
		}

		// ACTUALIZAR SESSION
		private function updateSession($session_id){
			global $session_control_config;
			
			$now = date('Y-m-d H:i:s');
			$expiration_time = $session_control_config['expiration_time'];
			$expire = date("Y-m-d H:i:s", (time() + $expiration_time));
			$maquina = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
			$agent = $_SERVER['HTTP_USER_AGENT'];

			$sql = "UPDATE session_control SET actualizacion = '$now', duracion = $expiration_time, 
						expiracion = '$expire', expirada = 0, maquina = '$maquina', user_agent = '$agent' 
					WHERE token = '$session_id'";

			return $this->model->exec($sql);
		}

		// permitir la entrada de una session
		private function allowSession($session){
			if( $session ){ $this->updateSession( $session['token']); }
			else { $this->registrarSession( session_id() );}
		}

		// obtener licencias activas
		private function getActiveLicences(){
			$now = date('Y-m-d H:i:s');
			$sql = "SELECT * FROM licencia WHERE expirada = 0 and inicio <= '$now'";
			$res = $this->model->query($sql);
			return ($res->fetchAll(PDO::FETCH_ASSOC));
		}

		// obtener cantidad de licencias activas
		private function getActiveLicencesNumber(){
			$lic = $this->getActiveLicences();
			$num = 0;
			foreach ($lic as $key => $l) {
				$num += $l['cantidad'];
			}
			return $num;
		}

		// VERIFICAR SI UNA SESSION EXISTE
		private function getSession($session_id){
			$sql = "SELECT * FROM session_control WHERE token = '$session_id'";
			$query = $this->model->query($sql);
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if( $res ){
				return $res;
			}
			return null;
		}

		// verifica si una sesion esta abierta
		private function sessionIsOpen($session){
			return !($session['expirada'] == 1);
		}

		// obtener sesiones abiertas
		private function getOpenSessions(){
			$sql = "SELECT * FROM session_control WHERE expirada = 0";
			$query = $this->model->query($sql);
			return ($query->fetchAll(PDO::FETCH_ASSOC));
		}
		
		// obtener nuemero de licencias abiertas
		private function getOpenSessionsNumber(){
			$sesions = $this->getOpenSessions();
			return count($sesions);
		}

		// retorna el tiempo de expiracion de la siguiente session
		private function getNextSessionExpireTime($sessions){
			$time = 0;
			usort($sessions, function($s1, $s2){ return $s1->expiracion - $s2->expiracion; });
			return strtotime($sessions[0]['expiracion']) - time();
		}

		function session_control_logout($destroy_session = false){
			$this->revokeSession( session_id() );
			if( $destroy_session ){
				session_destroy();
				$_SESSION = array();
			}
		}

		private function getTemplateForMaximumLicences(){
			global $session_control_config;
			if( $session_control_config['template_is_file'] ){
				$tpl = file_get_contents( $session_control_config['template_maximun_licences'] );
			}else{
				$tpl =  $session_control_config['template_maximun_licences'];
			}
			return $tpl;
		}

		private function setLicencesToTemplate($tpl, $numero_lic){
			global $session_control_config;
			$licencias = " <b>($numero_lic)</b>";
			return str_replace("{{ \$licencias }}", $licencias, $tpl);
		}

		private function setNextSessionExpireTimeToTemplate($tpl, $sessions, $numero_lic = 0){
			global $session_control_config;
			$timeNextExpire = $numero_lic > 0 
								? " <b>en ".$this->getNextSessionExpireTime($sessions)." segundos</b>"
								: "";
			return str_replace("{{ \$timeNextExpire }}", "$timeNextExpire", $tpl);
		}

	}

 ?>
