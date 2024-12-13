<?php
include('config.php');

// Get parameters from DataTables
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$orderColumn = $_POST['order'][0]['column'];
$orderDir = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

// Define columns
$columns = [
    'shipment_id', 'origin', 'destination', 'receipt_no', 'date', 'status', 
    'shipper_name', 'shipper_contact', 'consignee_name', 'consignee_contact', 
    'weight', 'pieces', 'mode_of_payment', 'rate', 'local_charges', 
    'packing', 'total_amount', 'office'
];

// Build base query
$query = "SELECT s.shipment_id, s.date, s.origin, s.destination, s.receipt_no, s.shipper_name, 
          s.shipper_contact, s.consignee_name, s.consignee_contact, s.weight, s.pieces, 
          s.mode_of_payment, s.status, s.rate, s.local_charges, s.packing, s.total_amount, 
          s.office 
          FROM shipments s
          JOIN users u ON s.origin = u.city
          WHERE u.userid = ?";

// Apply search filter
if (!empty($searchValue)) {
    $query .= " AND (s.shipment_id LIKE ? OR s.origin LIKE ? OR s.destination LIKE ?)";
}

// Order data
$query .= " ORDER BY {$columns[$orderColumn]} $orderDir LIMIT ?, ?";

// Prepare and execute
$stmt = $con->prepare($query);
$searchParam = "%$searchValue%";
if (!empty($searchValue)) {
    $stmt->bind_param("issii", $_SESSION['userid'], $searchParam, $searchParam, $searchParam, $start, $length);
} else {
    $stmt->bind_param("iii", $_SESSION['userid'], $start, $length);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Get total records
$totalQuery = "SELECT COUNT(*) AS total FROM shipments s JOIN users u ON s.origin = u.city WHERE u.userid = ?";
$stmtTotal = $con->prepare($totalQuery);
$stmtTotal->bind_param("i", $_SESSION['userid']);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalRecords = $totalResult->fetch_assoc()['total'];

// Prepare response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $data
];

echo json_encode($response);
?>
