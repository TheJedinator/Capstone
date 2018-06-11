<?php
include("util.php");
include("connect.php");
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bruce's TimeSheet App</title>
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
			//Userinfo form submission and variable extraction. Posts to controller signup function. 
			$("#userinfo").submit(function(e) {
				e.preventDefault();
				var studentid = $("#studentid").val();
				var first = $("#firstname").val();
				var last = $("#lastname").val();
				var pw1 = $("#newpw1").val();
				var pw2 = $("#newpw2").val();
				if (pw1 == pw2) {
				$.ajax({
					type: 'POST',
					url: 'controller.php',
					dataType: 'text',
					data:{
						functionToCall: "SignUp",
						uid: studentid,
						fname: first,
						lname: last,
						pass: pw2
					},
					cache: false,
					success: function (data){
						alert(data);
						if (data == "Sign Up Complete"){
							setTimeout(3000);
							window.location.replace("index.php")
						}
					},
					error: function(xhr,ajaxOptions, thrownError){
						alert(xhr.status + "\n" + thrownError);
						return false;
					}
				});
			}else {
				alert("Passwords Do Not Match")
			}
				return false;
			
			});
		});
	</script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h3 align="center">
					Welcome to Bruce's Timesheet Application
				</h3>

				<div class="col-md-6">
					<p> Please sign up using your student ID</p>
					<form id="userinfo" action="" method="post" class="form-horizontal" role="form">
						<div class="form-group">
							<label for="studentid"  class="col-sm-2 control-label">
								Student ID: 
							</label>
							<div class='col-sm-10'>
								<input type="number" class="form-control" name="studentid" id="studentid" min="0" minlength="7" required maxlength="10">
							</div>
						</div>
						<div class="form-group">
							<label for="firstname"  class="col-sm-2 control-label"> First Name: </label>
							<div class='col-sm-10'>
								<input type="text" class="form-control" name="firstname" id="firstname" required maxlength="25">
							</div>
						</div>
						<div class="form-group">
							<label for="lastname"  class="col-sm-2 control-label"> Last Name: </label>
							<div class='col-sm-10'>
								<input type="text" class="form-control" name="lastname" id="lastname" required maxlength="25">
							</div>
						</div>
						<div class="form-group">

							<label for="newpw1" class="col-sm-2 control-label"> New Password: </label>
							<div class='col-sm-10'>
								<input type=password class="form-control" required name="newpw1" id="newpw1">
							</div>
						</div>
						<div class="form-group">
							<label for="newpw2" class="col-sm-2 control-label"> Confirm Password: </label>
							<div class='col-sm-10'>
								<input type=password class="form-control" required name="newpw2" id="newpw2">
							</div>
						</div>
						<div class='form-group'>
							<div class='col-sm-10'>
								<button type='submit' id='userinfo' name='userinfo' class='btn btn-default'>
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