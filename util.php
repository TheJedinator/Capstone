<?php
//retrieves previously entered times form the DB 
function getTimes($con, $id){
	$result =	mysqli_query($con, "CALL SP_getTimes($id)");
	$i = 0;
	if (mysqli_num_rows($result) != 0){
		while ($x = mysqli_fetch_assoc($result)){
			$array[] = $x;
		}
		return $array;
	} else return null;
}
//Prints out the user submitted times in a table with editable fields and buttons. 
function displayTimes($con, $id){
	$times = getTimes($con, $id);
	if ($times == null){
		echo "No Results";
	} else {
		$totalHours = 0;
		foreach ($times as $r){
			$totalHours += $r['hours'];
			echo
			"<tr>
			<td name='tabledate' id='tabledate".$r['entryID']."' contenteditable='true'>". substr($r['date'], 0,10) . "</td>
			<td name='tablehours' id='tablehours". $r['entryID']."' contenteditable='true'>". $r['hours'] ."</td>
			<td name='tabledesc' id='tabledesc".$r['entryID']."' contenteditable='true'>". $r['description'] . "</td>
			<td name='tablepay' id='tablepay". $r['entryID']. "'>
			<select>
			<option "; if ($r['pay_type'] == "PAID"){echo "selected='selected'";} echo " value='PAID'> PAID</option>
			<option "; if ($r['pay_type'] == "UNPAID"){echo "selected='selected'";} echo "value='UNPAID'> UNPAID</option>
			<option "; if ($r['pay_type'] == "HOLIDAY"){echo "selected='selected'";} echo "value='HOLIDAY'> HOLIDAY</option>
			<option "; if ($r['pay_type'] == "SICK"){echo "selected='selected'";} echo " value='SICK'> SICK</option>
			</select>
			</td>";
			echo "
			<td>
			<button type='button' id='".$r['entryID'] ."' name='saveTable' class='btn btn-success'>
			Save
			</button>
			</td>
			<td>
			<button type='button' id='d".$r['entryID'] ."' name='delRow' class='btn btn-danger' onclick='deleteHours()'>
			Delete
			</button>
			</td>
			</tr>";
		}
		echo "
		<tr>
		<td><strong> Total Hours:</strong> </td>
		<td><strong>". $totalHours . "</strong> </td>
		</tr>
		</tbody> </table>";
		
			echo "
			<button type='button' id='finalize' name='finalize' class='btn btn-warning'> FINALIZE HOURS </button>";
		
	}
}
//Function that gets the first day of last week. 
function getFirstDay(){
	$previous_week = strtotime("-1 week +1 day");

	$start_week = strtotime("last sunday midnight",$previous_week);
	$end_week = strtotime("next saturday",$start_week);

	$start_week = date("Y-m-d",$start_week);
	$end_week = date("Y-m-d",$end_week);

	return $start_week;
}
//Function that gets the last day of last week. 
function getLastDay(){
	$previous_week = strtotime("-1 week +1 day");

	$start_week = strtotime("last sunday midnight",$previous_week);
	$end_week = strtotime("next saturday",$start_week);

	$start_week = date("Y-m-d",$start_week);
	$end_week = date("Y-m-d",$end_week);
	return $end_week;
}
//This function blows apart a csv file to be read as single values and parsed...borrowed from a stack overflow thread. 
function readCSV($csvFile){
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle) ) {
		$line_of_text[] = fgetcsv($file_handle, 1024);
	}
	fclose($file_handle);
	return $line_of_text;
}
//Creates a student in the db
function insertStudent($con, $id, $first, $last, $password){
	$hashed = "";
	if ($password == 0){
		$hashed = password_hash("PASSWORD", PASSWORD_DEFAULT);
	}	
	else {
		$hashed = password_hash($password, PASSWORD_DEFAULT);
	}
	$result = mysqli_query($con, "CALL SP_BulkInsert($id,'$first','$last','$hashed')");
	if ($result > 0){
		return "Insert Complete";
	}
	else{
		return mysqli_error($con);
	}
}
//Function to retrieve a list of students from the DB. 
function getStudentList($con){
	$result = mysqli_query($con, "CALL SP_getAllUsers()");
	if (mysqli_num_rows($result) != 0){
		while ($x = mysqli_fetch_assoc($result)){
			$array[] = $x;
		}
	} else {
		$array = null;
	}
	while(mysqli_more_results($con)){
		mysqli_next_result($con);
	}
	return $array;
}
//Validates the admin us a user and has entered the correct password.
function validateAdmin($con, $un, $pw){
	$password = "";
	$user = "";
	$result = mysqli_query($con, "CALL SP_getAdmin('$un')");
	while ($x = mysqli_fetch_assoc($result)){
		$password = $x['password'];
		$user = $x['name'];
	}
	while(mysqli_more_results($con)){
		mysqli_next_result($con);
	}
	if (password_verify($pw, $password)){
		return true;
	}
	else return false;
}
//Function to print currently logged in users name. 
function printName($con, $id){
	$result = mysqli_query($con, "CALL SP_selectName($id)");
	while ($x = mysqli_fetch_assoc($result)){
		echo $x['first'] . " " . $x['last'];
	}
	while(mysqli_more_results($con)){
		mysqli_next_result($con);
	}
}
?>
