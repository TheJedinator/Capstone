<!DOCTYPE html>
<?php
include("util.php");
include("connect.php");
session_start();

if (!isset($_SESSION['adminLoggedIn']) || $_SESSION['adminLoggedIn'] == false) {
		header("location: adminLogin.php");
}
$un = $_SESSION['username'];
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Admin - The TimeSheet App</title>
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
				//call to drop all users on controller page.
				$("#dropusers").on("click", function () {
					var r = confirm("Are you 110% sure you want to drop ALL USERS? Action cannot be undone.");
					if (r == true) {
						//ajax
						$.ajax({
							type: 'POST',
							url: 'controller.php',
							dataType: "text",
							data: {
								functionToCall: "deleteALLusers",
							},
							cache: false,
							success: function (data) {
								//alert(data);
								$.notify(data, {
									className: 'success',
									globalPosition: 'top center',
									position: "t c"
								});
								setTimeout(3000);
							},
							error: function (xhr, ajaxOptions, thrownError) {
								alert(xhr.status + "\n" + thrownError);
								return false;
							}
						}); // end ajax call
					} else {
						//alert(data);
						$.notify("Operation Cancelled", {
							className: 'error',
							globalPosition: 'top center',
							position: "t c"
						});
						return false;
					}
				});// END DROP USER FORM
				//Delete single user from DB as selected. 
				$("#deleteUser").submit(function (e) {
					
					e.preventDefault();
					var stuid = $("#userName :selected").val();
					var r = confirm("Are you sure you wish to delete user " + stuid);
					if (r){
					$.ajax({
						type: 'POST',
						url: 'controller.php',
						dataType: "text",
						data: {
							functionToCall: "deleteSingleUser",
							id: stuid
						},
						cache: false,
						success: function (data) {
							//alert(data);
							$.notify(data, {
								className: 'success',
								globalPosition: 'top center',
								position: "t c"
							});
													setTimeout(function(){
    							window.location=window.location;
									},1500);
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(xhr.status + "\n" + thrownError);
							return false;
						}
					}); // end ajax call
				}
					return false;
				}); //END DEL USER FORM
				$("#updatePW").submit(function (e) {
					e.preventDefault();
					var stuid = $("#pwUN :selected").val();
					var pw1 = $("#pw1").val();
					var pw2 = $("#pw2").val();
					if (pw1 == pw2){
					$.ajax({
						type: 'POST',
						url: 'controller.php',
						dataType: "text",
						data: {
							functionToCall: "updatePW",
							id: stuid,
							newpw: pw2
						},
						cache: false,
						success: function (data) {
							//alert(data);
							$.notify(data, {
								className: 'success',
								globalPosition: 'top center',
								position: "t c"
							});
													setTimeout(function(){
    							window.location=window.location;
									},1500);
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(xhr.status + "\n" + thrownError);
							return false;
						}
					}); // end ajax call
					return false;
				} else {
					$.notify("Password's Do Not Match", {
								className: 'error',
								globalPosition: 'top center',
								position: "t c"
					});
				}
				}); //END DEL USER FORM
				$("#genreport").submit(function (e){
					var dateone = $("#startdate").val();
					var datetwo = $("#enddate").val();
					if (dateone > datetwo){
						$.notify("Start date must be BEFORE End date", {
								className: 'error',
								globalPosition: 'top center',
								position: "t c"
							});
						return false;
					}
				});
			});//END DOC READY
		</script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<a href='logout.php' class="btn btn-default"> Logout </a>
					<a href='adminChangePW.php' class="btn btn-default"> Change My Password </a>
					<h3 align="center">
						Administrator Operations
					</h3>

					<div class="col-md-6">
						<h4><u> Reporting </u></h4>
						<form id="generateReport" action="reporter.php" method="post" class="form-horizontal" role="form">
							<p>Generate a Report for Last Week</p>
							<div class='form-group'>
								<?php
								$start = getFirstDay();
								$end = getLastDay();
								echo
								"<input hidden type='date' name='startdate' value='$start'>
								<input hidden type='date' name='enddate' value='$end'>";
								?>
								<div class='col-sm-offset-2 col-sm-10'>
									<button type='submit' id='queryLastWeek' name='queryLastWeek' class='btn btn-primary'>
										Report Last Week
									</button>
								</div>
							</div>
						</form>
						<br><BR>
						<p> Submit Date Range for Report</p>
						<form id="genreport" action="reporter.php" method="post" class="form-horizontal" role="form">
							<div class="form-group">
								<label for="startdate"  class="col-sm-2 control-label"> Start Date: </label>
								<input type="date" name="startdate" id="startdate" required data-provide="datepicker">
							</div>
							<div class="form-group">
								<label for="enddate"  class="col-sm-2 control-label"> End Date: </label>
								<input type="date" name="enddate" id="enddate" required data-provide="datepicker">
							</div>
							<div class='form-group'>
								<div class='col-sm-offset-2 col-sm-10'>
									<button type='submit' id='querySubmit' name='querySubmit' class='btn btn-default'>
										Submit Query
									</button>
								</div>
							</div>
						</form>
						<br><br>

						<h4><u> Delete Users</u></h4>
						<p> Delete All users from the Database (Useful for end of term)</p>
						<div class='form-group'>
							<div class='col-sm-offset-2 col-sm-10'>
								<button id='dropusers' name='dropusers' class='btn btn-danger'>
									Drop All Users
								</button>
							</div>
						</div>
						<br><br><br>
						<p>Delete Specific User</p>
						<form id="deleteUser" action="" method="post" class="form-horizontal" role="form">
							<div class="form-group">
								<div class='col-sm-offset-2 col-sm-10'>
									<select name="userName" id="userName" required>
										<option value=""> Select </option>
										<?php
										$list = getStudentList($con);
										foreach ($list as $x) {
											echo "<option value='" . $x['student_id'] . "'>" . $x['first'] . " " . $x['last'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>

							<div class='form-group'>
								<div class='col-sm-offset-2 col-sm-10'>
									<button id='btndelUser' name='btndelUser' class='btn btn-danger'>
										Delete Selected User
									</button>
								</div>
							</div>
						</form>
					</div>

					<div class="col-md-6">
						<h4><u> Edit/Update Users </u></h4>
						<p> Bulk Upload User List exported from BrightSpace as CSV </p>

						<form action="csvList.php" method="post" enctype="multipart/form-data">
							<div class="form-group">
								
									<input type="file" name="classList" id="bulkup" accept=".csv" required>
								</div>
								<div class="form-group">
									<button type="submit" name="submitbulk" id="submitbulk" class="btn btn-default"> Upload Class List 
									</button>
								</div>
						</form>
						<BR>
					<p>Update User Password</p>
						<form id="updatePW" action="" method="post" class="form-vertical" role="form">
						<div class="form-group">
							<div class='col-sm-10'>
								<label for="pwnUN" class="col-sm-4 control-label"> Select User: </label>
								<select class="col-sm-2 form-control" name="pwUN" id="pwUN" required>
									<option value=""> Select </option>
									<?php
										$list = getStudentList($con);
										foreach ($list as $x) {
											echo "<option value='".$x['student_id']."'>".$x['first']." ".$x['last']."</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class='col-sm-10'>
							<label for="pw1">New   Password:</label>
							<input class="form-control" type="password" id="pw1" name="pw1" required maxlength="24">
						</div>
					</div>
						<div class="form-group">
							<div class='col-sm-10'>
							<label for="pw2">Re-Type Password:</label>
							<input class="form-control" type="password" id="pw2" name="pw2" required maxlength="24">
						</div>
					</div>
					<div class="form-group">
						<div class='col-sm-10'>
						<button type="submit" name="btnupdatePW" id="btnupdatePW" class="btn btn-primary"> Update Student Password
						</button>
					</div>
					</div>
						</form>
					</div>
				</div>
			</div>
	</body>
</html>