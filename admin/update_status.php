<?php
include('config.php');
if (!isset($_SESSION['userid'])) {
    header('location:login.php');
    exit();
}

if (isset($_POST['shipment_id'])) {
    $shipment_id = $_POST['shipment_id'];

    $query = "UPDATE shipments SET status = 'DELIVERED' WHERE shipment_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $shipment_id);

    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
}
?>
