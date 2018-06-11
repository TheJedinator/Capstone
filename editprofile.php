<?php
include("util.php");
include("connect.php");
session_start();
if (isset($_SESSION['userid'])){
$id = $_SESSION['userid'];
} else { 
	header("location: index.php");
	}
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Timesheet - Edit My Profile</title>
	<meta name="description" content="Source code generated using layoutit.com">
	<meta name="author" content="LayoutIt!">
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/scripts.js"></script>
	<script src="js/notify.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script>
		$(document).ready(function () {
			$("#changepw").submit(function (e) {
				e.preventDefault();
				var pw1 = $("#newpw1").val();
				var pw2 = $("#newpw2").val();
				if (pw1 == pw2){
					$.ajax({
						type: 'POST',
						url: 'controller.php',
						dataType: 'text',
						data: {
							functionToCall: "updatePW",
							id: <?php echo $id; ?>,
							newpw: pw2
						}, 
						cache: false,
						success: function (data){
							//alert(data);
							$.notify(data, {
						className: 'success',
						globalPosition: 'top center',
						position:"t c"
					});
						},
						error: function(xhr, ajaxOptions, thrownError){
							alert(xhr.status + "\n" + thrownError);
							return false;
						}
					});
					return false;
				} else {
					$.notify("Passwords do not Match", {
						className: 'error',
						globalPosition: 'top center',
						position:"t c"
					});
				}
			});
		});
	</script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h3 align="center">
					Edit Profile
				</h3>
				
			<div class="col-md-6">
				<a href="index.php" class="btn btn-default"> Home </a>
				<form name="changepw" id="changepw" action="" method="post" class="form-horizontal"> 
					<div class="form-group">
						<div class='col-sm-offset-2 col-sm-10'>
							<label for="newpw1" class="col-sm-4 control-label"> Enter New Password </label>
							<input type=password class="form-control" required name="newpw1" id="newpw1">
						</div>
					</div>
					<div class="form-group">
						<div class='col-sm-offset-2 col-sm-10'>
							<label for="newpw2" class="col-sm-4 control-label"> Confirm New Password </label>
							<input type=password class="form-control" required name="newpw2" id="newpw2">
						</div>
					</div>
					<div class="form-group">
						<div class='col-sm-offset-2 col-sm-10'>
							<button type='submit' id='btnSubPW' name='btnSubPW' class='btn btn-primary'>
								Submit
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