<?php 
	require_once '../includes/includes.php';
	$sql = "SHOW FULL TABLES FROM proyectos_de_grado";
	$tablas = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta($sql) );

	for($i=0; $i<count($tablas); $i++){
		$sql = "SHOW COLUMNS FROM ".$tablas[$i]['Tables_in_proyectos_de_grado'];
		$campos = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta($sql) );
		generarModelo($tablas[$i]['Tables_in_proyectos_de_grado']);
		echo '<h3>'.$tablas[$i]['Tables_in_proyectos_de_grado'].'</h3>';

		for($j=0; $j<count($campos); $j++){
			echo '<span>'.$campos[$j]['Field'].' -:- </span>';
		}
	}
	function generarModelo($nombreModelo){
		@mkdir("./misModelos/", 0775);
		$modelo = fopen("./misModelos/".$nombreModelo."Model.php", "a");
		$string = "<?php\n"
			."class ".$nombreModelo."Model{\n"
			."	public function Listar(\$datos='*',\$donde='1=1',\$ordenar=''){\n"
			."		\$sql = consulta::seleccionar(\$datos,'".$nombreModelo."',\$donde,\$ordenar);\n"
			."		\$res = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta(\$sql) );\n"
			."		return \$res;\n"
			."	}\n\n"
			."	public function Insertar(\$datos){\n"
			."		\$sql = consulta::insertar(\$datos,'".$nombreModelo."');\n"
			."		\$res = consulta::ejecutar_consulta(\$sql);\n"
			."		return \$res;\n"
			."	}\n\n"
			."	public function Actualizar(\$datos,\$donde=''){\n"
			."		\$sql = consulta::actualizar(\$datos,'".$nombreModelo."',\$donde);\n"
			."		\$res = consulta::ejecutar_consulta(\$sql);\n"
			."		return \$res;\n"
			."	}\n\n"
			."	public function Eliminar(\$donde='1=1'){\n"
			."		\$sql = consulta::eliminar('".$nombreModelo."',\$donde);\n"
			."		\$res = consulta::ejecutar_consulta(\$sql);\n"
			."		return \$res;\n"
			."	}\n"
			."}\n?>";
		fwrite($modelo, $string);
	}
	
 ?>