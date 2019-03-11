
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>Licencias exedidas</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style type="text/css">
		.card {
			width: 320px;
		}
	</style>
</head>
<body>

	<?php  
		// numero de licencias contratadas
		$numero_lic = ( ! empty($_GET['num_lic']) ) ? "({$_GET['num_lic']})" : ''; 
	?>

	<div class="container mt-5 d-flex justify-content-center">
		<div class="card bg-secondary text-white">
			<div class="card-body">
				<h5 class="card-title">Licencias Exedidas</h5>
				<hr>
				<p>
					Lo sentimos, <br>
					el número de licencias contratadas <?php echo $numero_lic; ?> se ha exedido.
				</p>
			</div>
		</div>
	</div>

</body>
</html>