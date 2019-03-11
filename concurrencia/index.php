<?php session_start(); require_once "session_control.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="bg-secondary d-flex justify-content-center align-items-center">
	
	<div class="card card-login mt-5 shadow animated fadeIn" style="width: 320px;">
		<div class="card-body">
			<h5 class="card-title text-primary text-center">Singin</h5>
			<hr>
			<form class="mb-4" method="post" action="crud_licencias.php">
				<div class="form-group mb-4">
					<label>Username</label>
					<input type="email" class="form-control" placeholder="Enter Email">
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" class="form-control" placeholder="Password">
				</div>
				<div class="form-group d-flex">
					<button type="submit" class="btn btn-primary px-4">Enter</button>
					<a href="#" class="card-link">Forgot Password</a>
				</div>
			</form>
			<!-- buttons -->
			<div class="d-flex justify-content-between align-items-center">
			</div>
		</div>
	</div>
		
</body>
</html>