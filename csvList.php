<?php
include("util.php");
include("connect.php");
//This will read the CSV file and upload it to the Database.
	$csvFile = $_FILES['classList']['tmp_name'];
	
	$f = readCSV($csvFile);
	//echo $f[1][0] . " " . $f[1][1];
	$i = 1;
	while ($i < (sizeof($f) - 1)){
		//echo $f[$i][0] . " " . $f[$i][1] . "  " . $f[$i][3] ."  <BR>";
		$first = $f[$i][0];
		$last = $f[$i][1];
		$id = intval($f[$i][3]);
		$password = 0;
		insertStudent($con, $id, $first, $last, $password);
		$i++;
	}
	if ($i == (sizeof($f)-1)){
		echo "Bulk Upload Complete";
	}
	else {
		echo "Bulk Upload Failed";
	}

?>