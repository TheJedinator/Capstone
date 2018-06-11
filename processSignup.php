<?php 
include("connect.php");
		$id = $_POST['studentid'];
		$first = $_POST['firstname'];
		$last = $_POST['lastname'];
	$hashed = password_hash("PASSWORD", PASSWORD_DEFAULT);
	$result = mysqli_query($con, "CALL SP_insertStudent($id,'$first','$last','$hashed')");
	if ($result > 0){
		echo "Sign Up Complete";
	}
	else{
		echo mysqli_error($con);
	}
?>