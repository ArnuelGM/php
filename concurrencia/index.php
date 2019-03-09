<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Control de Concurrencia</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
	
	<div class="container mt-5">
		<div class="row">
			<div class="col-12">
				<a href="logout.php" class="btn btn-primary">Log Out</a>
				<p class="lead text-center">
					ESTAS VIENDO LA VENATANA DE INICIO
				</p>
				<!-- <?php //session_start(); ?> -->
				<?php echo $_COOKIE[session_name()]; ?>
				<pre><code><?php echo json_encode($_SERVER, JSON_PRETTY_PRINT); ?></code></pre>
			</div>
		</div>
	</div>

</body>
</html>