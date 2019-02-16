<?php 
	require_once '../includes/includes.php';
	$sql = "SHOW FULL TABLES FROM proyectos_de_grado";
	$tablas = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta($sql) );
	for($i=0; $i<count($tablas); $i++){
		
		$nombreTabla = $tablas[$i]['Tables_in_proyectos_de_grado'];

		$sql = "SHOW COLUMNS FROM ".$nombreTabla;
		$resCampos = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta($sql) );
		$campos = array();
		for($j=0; $j<count($resCampos); $j++){
			array_push($campos, $resCampos[$j]['Field']);
		}
		$nombre_table = $nombreTabla;
		$atributos = $campos;
		generarVistaFormulario($nombre_table,$atributos);
	}
	function generarVistaFormulario($tabla, $atrib){
		@mkdir("./views/", 0775);
		$vista = fopen("./views/form_".$tabla.".html", "a");
		echo "<p>$tabla</p>";
		echo "<p>".json_encode($atrib)."</p>";
		echo "<hr>";
		
		$string = "<form class='pure-form pure-form-stacked' id='form_".$tabla."'>\n";
		$string .= "	<fieldset>\n";
		$string .= "		<legend><h4>$tabla</h4></legend>\n";
		$string .= "		<div class='row'>\n";
			for ($x=0; $x < count($atrib); $x++) { 
				$campo = $atrib[$x];
				$string .= ""
				."			<div class='col-xs-3'>\n"
				."				<label for='$campo'>$campo</label>\n"
				."				<input type='text' id='$campo' name='$campo' />\n"
				."			</div>\n";
			}
		$string .= "		</div>\n";
		$string .= "		<div class='row'>\n";
		$string .= "			<div class='col-xs'>\n";
		$string .= "				<button type='submit' class='pure-button pure-button-primary'>Guardar</button>\n";
		$string .= "			</div>\n";
		$string .= "		</div>\n";
		$string .= "	</fieldset>\n";
		$string .= "</form>";

		fwrite($vista, $string);
	}
	
 ?>