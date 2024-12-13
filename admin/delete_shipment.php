<?php
include ('config.php');
if (!isset($_SESSION['userid'])) {
    header('location:login.php');
    exit();
}

if (isset($_POST['shipment_id'])) {
    $shipment_id = $_POST['shipment_id'];

    $query = "DELETE FROM shipments  WHERE shipment_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $shipment_id);

    if ($stmt->execute()) {
        echo "Shipment deleted successfully";
    } else {
        echo "Error deleteing status: " . $stmt->error;
    }

    $stmt->close();
}
?>