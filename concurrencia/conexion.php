<?php 

	$con = new mysqli('localhost', 'root', '', 'concurrencia');
	// mysql_selectdb('curso_php');

	function asociativo($query){
		$resultado = [];
		$query->data_seek(0);
		while ($row = $query->fetch_assoc()) {
			array_push($resultado, $row);
		}
		return $resultado;
	}
	
	function pre($code){
		echo "<pre><code>" . json_encode($code, JSON_PRETTY_PRINT) . "</code></pre>";
	}

 ?>