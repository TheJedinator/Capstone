<!DOCTYPE html>
<?php
include("util.php");
include("connect.php");
session_start();
if (isset($_SESSION['loggedin'])){
	$loggedin = $_SESSION['loggedin'];
	echo "<script>
	var loginGood =".$loggedin.";
	</script>";
	$loggedin = true;
	$id = $_SESSION['userid'];
}
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TimeSheet App</title>
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
			if (typeof loginGood !== 'undefined'){
				if (loginGood == true || loginGood == 1){
					$("#inputTimeForm").show();
					$("#loginForm").hide();
				}
			}
			//Login form submission calls login.php with id and password
		$("#loginForm").submit(function(e){
					e.preventDefault();
					//JS variables for input to be posted to the API
					var id = $("#inputStudentID").val();
					var pw = $("#inputpassword").val();
					//e.preventDefault();
					$.ajax({
						type: 'POST',
						url: 'login.php',
						dataType: "text",
						data: {login: id, password: pw},
						cache: false,
						success: function (data) {
							
							if (data == "TRUE"){
								loginGood = true;
								location.reload();
							}else {
								alert(data);
							}
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(xhr.status + "\n" + thrownError);
							return false;
						}
						}); // end ajax call
				

					
		}); //end Login
		//ON SUBMIT OF HOURS posts to controller.php and calls submit hours function with values from form. 
		$("#submitHours").submit(function(e) {
			e.preventDefault();
			var paid = $("input[type=radio][name=payType]:checked" ).val();
				//AJAX CALL PARAMS = function, date input, hours input, task input, current user
				$.ajax({
					type: 'POST',
					url: 'controller.php',
					dataType: "text",
					data: {
						functionToCall: "submitHours",
						date: $("#dateIn").val(),
						hours:$("#hours").val(),
						task: $("#task").val(),
						paid: paid,
						user: $("#userID").val()
					},
					cache: false,
					success: function (data) {
						//alert(data);

						$.notify(data, {
						className: 'success',
						globalPosition: 'top center',
						position:"t c"
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
			});
		//ON CLICK SAVE BUTTON
		//Save changes to the time in the table. POSTS to controller and calls update hours 
		$("button[name='saveTable']").on("click", function(){
			var rowid = $(this).attr('id');
			var vDate = $("#tabledate"+rowid).text();
			var vhours = $("#tablehours"+rowid).text();
			var vTask = $("#tabledesc"+rowid).text();
			var vPaid = $("#tablepay"+rowid+" option:selected").val();
			var uid = $("#userID").val();
			//alert(rowid + "|" + vDate + "|" + vhours + "|" + vTask + "|" + vPaid + "|" + uid);
			$.ajax({
				type: 'POST',
				url: 'controller.php',
				dataType: "text",
				data: {
					functionToCall: "updateHours",
					date: vDate,
					hours: vhours,
					task: vTask,
					paid: vPaid,
					row: rowid
				},
				cache: false,
				success: function (data) {
					$.notify(data, {
						className: 'success',
						globalPosition: 'top center',
						position:"t c"
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
		});//end save table function
		//ON delete row button click deletes entry from DB by calling delete hours function on controller
		//Takes in row # of current entry. Which is provided by the id # for the entry. 
		$("button[name='delRow']").on("click", function(){
			var rowid = $(this).attr('id');
			//removes preceeding "d" character that indicates it's a delete button.
			rowid = rowid.substring(1);
			//AJAX CALL
			$.ajax({
				type: 'POST',
				url: 'controller.php',
				dataType: "text",
				data: {
					functionToCall: "deleteHours",
					row: rowid
				},
				cache: false,
				success: function (data) {
					$.notify(data, {
						className: 'success',
						globalPosition: 'top center',
						position:"t c"
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
		});
		//Finalize function
		//Finalize the hours, calls finalize function on controller page. 
		$("#finalize").on("click", function(){
			var r = confirm("Are you 110% sure these are the hours you want to submit, this action cannot be undone!?")
			if (r == true){
				//ajax
				$.ajax({
					type: 'POST',
					url: 'controller.php',
					dataType: "text",
					data: {
						functionToCall: "finalizeHours",
						id: $("#userID").val()
					},
					cache: false,
					success: function (data) {
						//alert(data);
						$.notify(data, {
							className: 'success',
							globalPosition: 'top center',
							position:"t c"
						});
							setTimeout(function(){
    							window.location=window.location;
									},2500);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + thrownError);
						return false;
					}
				}); // end ajax call
			}
			else {
				$.notify("Operation Cancelled", {
					className: 'error',
					globalPosition: 'top center',
					position:"t c"
				});
				return false;
			}
		});
		
		}); // end doc ready
	</script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h3 align="center">
					Welcome to the Timesheet Application
				</h3>
				<a href="logout.php" class='btn btn-default'> Logout </a>
				<?php 
				if (isset($_SESSION['loggedin'])){
						echo "<a class='btn btn-default' href='editprofile.php'> Edit Profile </a>";
					
					}
				?>
				<a href="adminLogin.php" class="btn btn-default"> Admin </a>
				<p> <?php if (isset($_SESSION['loggedin']) && ($_SESSION['loggedin']) == true){
					echo "Hello <b>"; 
					printName($con, $id);
					echo "</b><BR>Please submit your times";
				} else {
					echo "Please sign in";
				}?>  </p>
				<div class="row">
					<!-- LOGIN FORM -->
					<div id="loginForm" class="col-md-6">
						<form  name="loginForm" action="" method="post" class="form-horizontal">
							<div class="form-group">
								<label for="inputStudentID" class="col-sm-2 control-label">
									Student ID:
								</label>
								<div class="col-sm-10">
									<input type="number" class="form-control" name="inputStudentID" id="inputStudentID" required maxlength="8" placeholder="1234567">
								</div>
							</div>
							<div class="form-group">
								<label for="inputpassword" class="col-sm-2 control-label">
									Password:
								</label>
								<div class="col-sm-10">
									<input type="password" class="form-control" id="inputpassword" name='inputpassword'  maxlength='24' required>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" id="loginSubmit" name="loginSubmit" class="btn btn-default">
										Sign in
									</button>
								</div>
							</div>
						</form>

						<!-- LINK TO SIGN UP PAGE-->
						<div class="col-sm-10">
							<a href="signup.php"> Not a member? </a>
						</div>
					</div>
					<!-- LOGIN FORM ENDS -->
					<!-- INPUT TIME FORM -->
					<div id="inputTimeForm" class="col-md-6" hidden="true">
						<form id="submitHours" action="" method="post" class="form-horizontal" role="form">
							<!-- DATE INPUT -->
							<div class="form-group">
								<label for="inputDate"  class="col-sm-2 control-label">
									Date:
								</label>
								<div class="col-sm-10">
									<input type="date" min="2018-01-01" max="2018-06-01" name="dateIn" id="dateIn" required data-provide="datepicker">
									<input type="hidden" name="functionToCall" id="functionToCall" value="submitHours">
								</div>
							</div>
							<!-- TASK INPUT -->
							<div class="form-group">
								<label for="task" class="col-sm-2 control-label">
									Task:
								</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="task" name="task" maxlength="50" required>
								</div>
							</div>
							<!-- HOURS INPUT -->
							<div class="form-group">
								<label for="hours" class="col-sm-2 control-label">
									Hours:
								</label>
								<div class="col-sm-6">
									<input type="number" class="form-control" name="hours" id="hours" required max="30" min="1" placeholder="00" >
								</div>
							</div>
							<!-- PAID/UNPAID checkbox -->
							<div class="col-sm-6">
								<div class="form-check">
									<input class="form-check-input" name="payType" type="radio" id="paid" value="PAID">
									<label class="form-check-label" for="payType">Paid</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" checked name="payType" type="radio" id="unpaid" value="UNPAID">
									<label class="form-check-label" for="payType">Un-Paid</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" name="payType" type="radio" id="holiday" value="HOLIDAY">
									<label class="form-check-label" for="payType">Holiday</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" name="payType" type="radio" id="Sick" value="SICK">
									<label class="form-check-label" for="payType">Sick</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" name="payType" type="radio" id="Overtime" value="OVERTIME">
									<label class="form-check-label" for="payType">Overtime</label>
								</div>
								<!-- hidden label to hold user id-->
								<input type="hidden" id="userID" name="userID" value="<?php echo $id ?>">
							</div>
							<!-- SUBMIT BUTTON -->
							<div class='form-group'>
								<div class='col-sm-offset-2 col-sm-10'>
									<button type='submit' id='hoursSubmit' name='hoursSubmit' class='btn btn-primary'>
										Submit
									</button>
								</div>
							</div>
							<!-- SUBMIT HOURS FORM ENDS -->
						</div>
						<div id="TimeDisplay" class="col-md-6">
							<form>
								<table class="table">
									<thead>
										<tr>
											<th> Date </th>
											<th> Hours</th>
											<th> Description </th>
											<th> Paid </th>
										</tr>
									</thead>
									<tbody>
										<!-- ROW -->
										<?php
										if (isset($_SESSION['loggedin'])){
											displayTimes($con, $id);
										} else {
											echo "<script> $('#TimeDisplay').hide(); </script>";
										}
										?>
										<!-- </tbody>
										</table> -->
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script src="js/jquery.min.js"></script>
			<script src="js/bootstrap.min.js"></script>
			<script src="js/scripts.js"></script>
			<script src="js/notify.js"></script>
		</body>
		</html>