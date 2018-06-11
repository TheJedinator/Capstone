<?php
session_start();
include("connect.php");
// This checks what function is being called by the AJAX on the calling page. 
//Then pulls the post variables and passes them to the functions

if (isset($_POST['functionToCall'])){
	//FUNCTION CALL TO submit HOURS
	
	if ($_POST['functionToCall'] == "submitHours"){
		$user = $_SESSION['userid'];
		$hours = $_POST['hours'];
		$task = $_POST['task'];
		$task_escaped = mysqli_real_escape_string($con, $task);
		$date = $_POST['date'];
		$paid = -1;
		if (isset($_POST['paid'])){
			$paid = $_POST['paid'];
		}
		else {
			$paid = "UNPAID";
		}
		$response = submitHours($con, $date, $task_escaped, $hours, $user, $paid);
			//echo " $date ||" . " $task||" . " $hours||" . " $user||" . " $paid    ";
	}

	if($_POST['functionToCall'] == "updateHours"){
		$rowid = $_POST['row'];
		$hours = $_POST['hours'];
		$date = $_POST['date'];
		$task = $_POST['task'];
		$task_escaped = mysqli_real_escape_string($con, $task);
		$paid = $_POST['paid'];
		$response = updateHours($con, $date, $task_escaped, $hours, $rowid, $paid);
	}
	if ($_POST['functionToCall'] == "deleteHours"){
		$rowid = $_POST['row'];
		$response = deleteHours($con, $rowid);
	}
	if ($_POST['functionToCall'] == "finalizeHours"){
		$id = $_POST['id'];
		$response = finalizeHours($con, $id);
	}

	if ($_POST['functionToCall'] ==  "deleteALLusers"){
		$response = deleteALLusers($con);
	}
	if ($_POST['functionToCall'] ==  "SignUp"){
		$id = $_POST['uid'];
		$first = mysqli_real_escape_string($con, $_POST['fname']);
		$last = mysqli_real_escape_string($con, $_POST['lname']);
		$pass = mysqli_real_escape_string($con,$_POST['pass']);
		$response = SignUp($con, $id, $first, $last, $pass);
	}
	if ($_POST['functionToCall'] == "deleteSingleUser"){
		$id = $_POST['id'];
		$response = deleteSingleUser($con, $id);
	}
	if ($_POST['functionToCall'] ==  "updatePW"){
		$id = $_POST['id'];
		$password = mysqli_real_escape_string($con, $_POST['newpw']);
		$response = updatePW($con, $id, $password);
	}
	if ($_POST['functionToCall'] ==  "adminUpdatePW"){
		$id = $_POST['adminid'];
		$password = mysqli_real_escape_string($con, $_POST['password']);
		$response = adminUpdatePW($con, $id, $password);
	}
	//Returns a response to the calling page.
	echo $response;
}
// Function for user to submit hours to the DB. 
function submitHours($con, $date, $task, $hours, $user, $paid){
	$date = substr($date, 0, 10);
	$result = mysqli_query($con, "CALL SP_insertHours('$task', $hours, $user, '$date', '$paid')");
	if ($result > 0){
		return "Hours Sucessfully Submitted";
	}
	else{
		return mysqli_error($con);
	}
}
//This will allow the user to update the info for hours, (Tasks, pay type etc.)
function updateHours($con, $date, $task, $hours, $rowid, $paid){
	$result = mysqli_query($con, "CALL SP_updateTime($rowid, '$date', $hours,'$task', '$paid')");
	if ($result > 0){
		return "Hours Sucessfully Updated";
	}
	else{
		return mysqli_error($con);
	}
	return mysqli_error($con);
}
//Deletes the hours that were previously submitted from the DB 
function deleteHours($con, $rowid){
	$result = mysqli_query($con, "CALL SP_deleteTime($rowid)");
	if ($result > 0){
		return "Succesfully removed Entry $rowid";
	}
	else {
		return mysqli_error($con);
	}
	return mysqli_error($con);
}
// Finalize hours so not able to be viewed by user but can be put in report. 
//User cannot undo this action, it's equivelant to handing in your timesheet. 
function finalizeHours($con, $id){
	$result = mysqli_query($con, "CALL SP_finalize($id)");
	if ($result > 0){
		return "Your Final Hours have been submitted";
	}
	else {
		return mysqli_error($con);
	}
	return mysqli_error($con);
}
//Function for Admin to delete a single user from the DB 
function deleteUser($con, $id){
	$result = mysqli_query($con, "CALL SP_dropUser($id)");
	if ($result > 0){
		return "User Deleted";
	}
	else{
		return mysqli_error($con);
	}
}
//Function for all the users to be dropped from the DB (END OF TERM)
function deleteALLusers($con){
	$result = mysqli_query($con, "CALL SP_wipeUsers()");
	if ($result > 0){
		return "ALL USERS HAVE BEEN REMOVED!!!";
	}
	else{
		return mysqli_error($con);
	}
}
// Allows a user to sign up for the service. 
function SignUp($con, $id, $first, $last, $password){
	$hashed = password_hash($password, PASSWORD_DEFAULT);
	$result = mysqli_query($con, "CALL SP_insertStudent($id,'$first','$last','$hashed')");
	if ($result > 0){
		return "Sign Up Complete";
	}
		return mysqli_error($con);
}
//Delete single user. 
function deleteSingleUser($con, $id){
	$result = mysqli_query($con, "CALL SP_DeleteUser($id)");
	if ($result > 0){
		return "User ". $id . " has been removed";
	}
		return mysqli_error($con);
}
//Allows user to changer their password
function updatePW($con, $id, $pw){
	$hashed= password_hash($pw, PASSWORD_DEFAULT);
	$result = mysqli_query($con, "CALL SP_updatePassword($id,'$hashed')");
	if ($result > 0){
		return "Password Updated";
	}
		return mysqli_error($con);
}
//Admin able to update change their password
function adminUpdatePW($con, $name, $pw){
	$hashed= password_hash($pw, PASSWORD_DEFAULT);
	$result = mysqli_query($con, "CALL SP_adminUpdatePW('$name','$hashed')");
	if ($result > 0){
		return "Password Updated";
	}
		return mysqli_error($con);
}
?>
