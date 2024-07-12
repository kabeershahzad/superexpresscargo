<?php
if(session_status() === PHP_SESSION_NONE){
    // If no session exists, start one
    session_start();
}

$con = new mysqli('localhost', 'root', '') or die("Unable to connect");

if (!mysqli_select_db($con, 'superexp_db')) {
    echo "Database not selected";
}
?>
