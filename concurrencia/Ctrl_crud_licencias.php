<?php 
	
	session_start();
	require_once "conexion.php";
	
	function getLicences(){
		global $con;
		return asociativo($con->query("SELECT * FROM licencia WHERE expirada = 0"));
	}

	function registerNewLicence(){
		global $con;
		if( empty($_POST) ) return;
		$now = date('Y-m-d H:i:s');
		$fh = 3600*5;
		$tipo = $_POST['tipo'];
		$cant = $_POST['cantidad'];
		$fi = date('Y-m-d H:i:s', (strtotime($_POST['fecha_inicio']) - $fh));
		$ff = date('Y-m-d H:i:s', (strtotime($_POST['fecha_expiracion']) - $fh));
		$obs = $_POST['observacion'];
		$sql = "INSERT INTO licencia 
			(tipo, cantidad, creacion, fecha_inicio, fecha_expiracion, expirada, observacion)
		VALUES ('$tipo', $cant, '$now', '$fi', '$ff', 0, '$obs')";
		return $con->query($sql);
	}
	
 ?>