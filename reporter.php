<?php
 //makes a report from the query
require ('Classes/PHPExcel.php');
include("connect.php");
include("util.php");

//Assign variables from the form  and format date for query
$date = date_create('2000-01-01');
$start = $_POST['startdate'];
$end = $_POST['enddate'];

//Call the function to get the report
generateReport($con, $start, $end);


// Query Function to get the report. Takes in date range and connection
function getReportDate($con, $startDate, $endDate){
	$reportResult = mysqli_query($con, "CALL SP_runDateReport('$startDate', '$endDate')");
	while ($x = mysqli_fetch_assoc($reportResult)){
		$array[] = $x;
	}
	while(mysqli_more_results($con)){
		mysqli_next_result($con);
	}
	if (isset($array)){
		return $array;
	}
}
// Creates the report as an excel file
function generateReport($con, $start, $end){
	 //Runs query function
	$data = getReportDate($con, $start, $end);
	if ($data != null){
	//Get a list of all students to user for sheet creation. 
	$list = getStudentList($con);
	//Create Excel Sheet instance
	$excel = new PHPExcel();
	//set sheet index to 0 
	$sheetIndex = 0;
	// Create a new sheet for each student in the database, populate top cells with headings. 
	foreach ($list as $student){

		$currentStudent_id = $student['student_id'];
		$row = 2;
		//Set the sheet title and populate the headings. 
		$excel->createSheet($sheetIndex);
		$excel->setActiveSheetIndex($sheetIndex)
		->setTitle($student['first']."_".$student['last'])
		->setCellValue("A1", "Date")
		->setCellValue("B1", "Student ID")
		->setCellValue("C1", "First Name")
		->setCellValue("D1", "Last Name")
		->setCellValue("E1", "Hours")
		->setCellValue("F1", "Description")
		->setCellValue("G1", "Pay Type");
		$sheetIndex++;
		//Loop through data returned by the query and set cell values per sheet if the student id is a match. 
		foreach ($data as $r){
			if ($currentStudent_id == $r['student_id']){
				$excel->getActiveSheet()
				->setCellValue("A".$row, substr($r['date'],0,10))
				->setCellValue("B".$row, $r['student_id'])
				->setCellValue("C".$row, $r['first'])
				->setCellValue("D".$row, $r['last'])
				->setCellValue("E".$row, $r['hours'])
				->setCellValue("F".$row, $r['description'])
				->setCellValue("G".$row, $r['pay_type']);
				$row++;
			}
		}
	}
	//Removes the blank sheet
	$excel->removeSheetByIndex($sheetIndex);

	 //HTTP header directions to save the EXCEL file
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="report_FROM_'.$start.'_TO_'.$end.'.xlsx"');
		header('Cache-Control: max-age=0');
		$file = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
	 // WILL NOT FORMAT CORRECTLY WITHOUT THIS LINE, DO NOT REMOVE!!!!
		ob_end_clean();
	 //************************************************************
		$file->save('php://output');
	}
	else {
		echo "No results for $start - $end";
	}
}
	?>
