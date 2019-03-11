<?php 
	session_start();
	require_once "session_control.php";
	require_once "Ctrl_crud_licencias.php"; 
	registerNewLicence();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Licencias</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
	<div class="container mt-4">
		<div class="row">
			<div class="col-sm-4">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Licencias</h5>
						<hr>
						<form method="post">
							<div class="form-group">
								<label>Tipo</label>
								<select name="tipo" class="form-control">
									<option value="FIJA">Fija</option>
									<option value="CONCURRENTE">Concurrente</option>
								</select>
							</div>
							<div class="form-group">
								<label>Cantidad</label>
								<input type="number" name="cantidad" class="form-control">
							</div>
							<div class="form-group">
								<label>Fecha de Inicio</label>
								<input type="datetime-local" name="fecha_inicio" class="form-control">
							</div>
							<div class="form-group">
								<label>Fecha Expiración</label>
								<input type="datetime-local" name="fecha_expiracion" class="form-control">
							</div>
							<div class="form-group">
								<label>Observación</label>
								<textarea name="observacion" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<button class="btn btn-success" type="submit">Guardar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Tipo</th>
							<th>Cantidad</th>
							<th>Fecha Inicio</th>
							<th>Fecha Expiración</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach (getLicences() as $i => $lic): ?>
							<tr>
								<td><?php echo $i+1 ?></td>
								<td><?php echo $lic['tipo'] ?></td>
								<td><?php echo $lic['cantidad'] ?></td>
								<td><?php echo $lic['fecha_inicio'] ?></td>
								<td><?php echo $lic['fecha_expiracion'] ?></td>
								<td>
									<a href="#" class="btn btn-link">Eliminar</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>