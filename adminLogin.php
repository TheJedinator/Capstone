<?php
include("util.php");
include("connect.php");
session_start();
//redirect user to admin page if logged in already. 
if (isset($_SESSION['adminLoggedIn']) && $_SESSION['adminLoggedIn'] == true){
	header("location: admin.php");
}
//Login logic. 
if (isset($_POST['username'])){
	$un = $_POST['username'];
	$pw = $_POST['password'];
	if (validateAdmin($con, $un, $pw)){
		$_SESSION['adminLoggedIn'] = true;
		$_SESSION['username'] = $un;
		header("location: admin.php");
	}
	else {
		$_SESSION['adminLoggedIn'] = false;
		echo "<script> 
				alert('Login Failed, Try again');
			</script>";
	}
}
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Timesheet - Admin Login</title>
	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/scripts.js"></script>
	<script src="js/notify.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h3 align="center">
					Admin Login Page
				</h3>
				
			<div class="col-md-6">
				<a href="index.php" class="btn btn-default"> Home </a>
				<form name="adminLogin" id="adminLogin" action="" method="post" class="form-horizontal"> 
					<div class="form-group">
						<label for="username" class="col-sm-2 control-label"> User Name: </label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="username" id="username" required maxlength="8" placeholder="username">
					</div>
				</div>
				<div class="form-group">
						<label for="password" class="col-sm-2 control-label"> Password: </label>
						<div class="col-sm-10">
						<input type="password" class="form-control" name="password" id="password" required maxlength="24" placeholder="password">
					</div>
				</div>
				<div class='form-group'>
					<div class='col-sm-offset-2 col-sm-10'>
							<button type='submit' id='loginSubmit' name='loginSubmit' class='btn btn-primary'>
								Login
							</button>
						</div>
					</div>
				</form>
			</div>
			</div>
		</div>
	</div>
</body>
</html>