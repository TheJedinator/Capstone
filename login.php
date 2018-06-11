<?php
	session_start();
	include("connect.php");
	$studentID = $_POST['login'];
	$pw = $_POST['password'];
//check user exists
	$rs1 = mysqli_query($con, "CALL SP_getStudentID($studentID)");
//check result is not false or -1
	while (mysqli_more_results($con)) {
		mysqli_next_result($con);
	}
	if ($rs1 != false && mysqli_num_rows($rs1) > 0){
		//if passed get password
		$rs2 = mysqli_query($con, "CALL SP_getPassword($studentID)");
			//password verify
		while ($x = mysqli_fetch_array($rs2)){
			$pass = $x['password'];
		}
		while (mysqli_more_results($con)) {
			mysqli_next_result($con);
		}
		if (password_verify($pw, $pass)){
			echo "TRUE";
			$_SESSION['loggedin'] = true;
			$_SESSION['userid'] = $studentID;
			exit;
		}
		else {
			echo "Login Failed";
		}
	}else {
		echo "User not found";
	}
?>
