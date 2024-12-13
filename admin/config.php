<?php
if(session_status() === PHP_SESSION_NONE){
    // If no session exists, start one
    session_start();
}

$con = new mysqli('localhost', 'u181616879_superexp', 'Superkabeer@1231') or die("Unable to connect");

if (!mysqli_select_db($con, 'u181616879_superexpdb')) {
    echo "Database not selected";
}
?>
