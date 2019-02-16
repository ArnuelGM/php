<?php 
	require_once '../includes/includes.php';
	$sql = "SHOW FULL TABLES FROM proyectos_de_grado";
	$tablas = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta($sql) );

	$string = "<?php\n"

			."	require_once '../Slim/Slim.php';\n"
			."	require_once '../includes/includes.php';\n"
			."	\Slim\Slim::registerAutoloader();\n"
			."	\$app = new \Slim\Slim();\n"
			."	\$app->contentType('application/json');\n\n";
	$rutas = "angular.module('')\n"
			.".factory('api', function(){\n"
			."	var api = {};\n";
	$forms = "angular.module('')\n"
			.".factory('\$f', function(){\n"
			."	var f = {};\n";

	for($i=0; $i<count($tablas); $i++){
		$nombreTabla = $tablas[$i]['Tables_in_proyectos_de_grado'];
		$sql = "SHOW COLUMNS FROM ".$nombreTabla;
		$resCampos = consulta::convertir_a_array_asociativo( consulta::ejecutar_consulta($sql) );
		$campos = array();
		for($j=0; $j<count($resCampos); $j++){
			array_push($campos, $resCampos[$j]['Field']);
		}

		$nombre_de_la_tabla = $nombreTabla;
		$atributos = $campos;
		$string .=	generarServicios($nombre_de_la_tabla, $atributos);
		$rutas .= generarRutasRest($nombre_de_la_tabla);
		$forms .= generarFormulario($nombre_de_la_tabla);
	}
	$string .= "	\$app->run();\n";
	$string .= "?>";

	$rutas .= "	return api;\n";
	$rutas .= "});";

	$forms .= "	return f;\n";
	$forms .= "});";

	@mkdir("./servicios/", 0775);
	$api = fopen("./servicios/api.php", "a");
	fwrite($api, $string);

	$rutasRest = fopen("./servicios/apiRest.js", "a");
	fwrite($rutasRest, $rutas);

	$formularios = fopen("./servicios/formularios.js", "a");
	fwrite($formularios, $forms);
	
	function generarServicios($tabla,$atrib){
		$return = ""
		."//**********Acciones para $tabla*******************************************\n\n"

		."	\$app->get('/$tabla', function(){\n"
		."		require_once '../models/".$tabla."Model.php';\n"
		."		\$model = new ".$tabla."Model();\n"
		."		\$lista = \$model->Listar();\n"
		."		echo json_encode(\$lista);\n"
		."	});\n";

		$return .= ""
		."	\$app->get('/$tabla/:id', function(\$id){\n"
		."		require_once '../models/".$tabla."Model.php';\n"
		."		\$model = new ".$tabla."Model();\n"
		."		\$donde = 'id='.\$id;\n"
		."		\$lista = \$model->Listar('*',\$donde);\n"
		."		echo json_encode(\$lista);\n"
		."	});\n";

		$return .= ""
		."	\$app->delete('/$tabla/:id', function(\$id){\n"
		."		require_once '../models/".$tabla."Model.php';\n"
		."		\$model = new ".$tabla."Model();\n"
		."		\$donde = 'id='.\$id;\n"
		."		\$result = \$model->Eliminar(\$donde);\n"
		."		echo json_encode(\$result);\n"
		."	});\n";

		$return .= ""
		."	\$app->post('/$tabla', function(){\n"
		."		require_once '../models/".$tabla."Model.php';\n"
		."		\$model = new ".$tabla."Model();\n"
		."		\$app = \Slim\Slim::getInstance();\n"
		."		\$post = \$app->request->getBody();\n"
		."		\$datos = array();\n"
		."		parse_str(\$post, \$datos);\n"
		."		unset(\$datos['id']);\n"
		."		\$result = \$model->Insertar(\$datos);\n"
		."		exit(json_encode(\$result));\n"
		."	});\n\n";

		$return .= ""
		."	\$app->put('/$tabla', function(){\n"
		."		require_once '../models/".$tabla."Model.php';\n"
		."		\$model = new ".$tabla."Model();\n"
		."		\$app = \Slim\Slim::getInstance();\n"
		."		\$post = \$app->request->getBody();\n"
		."		\$datos = array();\n"
		."		parse_str(\$post, \$datos);\n"
		."		\$donde = 'id = '.\$datos['id'];\n"
		."		\$result = \$model->Actualizar(\$datos,\$donde);\n"
		."		exit(json_encode(\$result));\n"
		."	});\n\n";

		return $return;
	}

	function generarRutasRest($tablaActual){
		return "//*********** RUTAS PARA $tablaActual ***************//\n"
		."	api.obtener_$tablaActual = 'servicios/api.php/$tablaActual';\n"
		."	api.crear_$tablaActual = 'servicios/api.php/$tablaActual';\n"
		."	api.actualizar_$tablaActual = 'servicios/api.php/$tablaActual/';\n"
		."	api.eliminar_$tablaActual = 'servicios/api.php/$tablaActual/';\n";
	}

	function generarFormulario($formulario){
		return "//*********** FORMULARIO PARA $formulario ***************//\n"
		."	f.$formulario = 'form_$formulario';\n";
	}
	
 ?>