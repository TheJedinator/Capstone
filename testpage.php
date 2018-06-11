<?php
include("connect.php");
include("util.php");
$array = getStudentList($con);
echo "<pre>";
print_r($array);
echo count($array);
echo "</pre>";
?>
