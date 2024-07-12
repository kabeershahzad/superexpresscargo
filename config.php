<?php
$con=new mysqli('localhost','root','')or die("Unable to connect");

if (!mysqli_select_db($con,'superexp_db')) {
	// code...
	echo "Database not selected";
}
?>