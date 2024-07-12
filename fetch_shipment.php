<?php
header('Content-Type: application/json');

// Get the document ID from the URL
$documentId = $_GET['id'];

// Database connection
include('config.php');

$query = "SELECT * FROM shipments where receipt_no = '$documentId'";
$result = mysqli_query($con, $query);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo json_encode($row);
} else {
  echo json_encode(null);
}


?>
