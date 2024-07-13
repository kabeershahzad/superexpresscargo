<?php
//session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('location: login.php');
    exit();
}

$userid = $_SESSION['userid'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <style>
      body {
        padding-top: 60px;
      }
      tr {
        cursor: pointer;
        font-family:Verdana, Geneva, Tahoma, sans-serif;
        font-size: 15px;
        
      }
     
    </style>
  </head>
  <body>
  

    <div class="container">
      <div class="input-group mt-3">
        <input
          type="text"
          class="form-control"
          id="search-input"
          placeholder="Search"
        />
      </div>
      <div class="dropdown mt-5">
        <label for="entries-per-page">Entries per page:</label>
        <select id="entries-per-page">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
      <div>
        <button class="btn btn-success mt-5" id="export-btn">
          Export In Excel
        </button>
      </div>
      <table class="table table-striped" id="data-table">
        <thead>
          <tr>
            <th>Shipment Id</th>
            <th>Date</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Receipt#</th>
            <th>Shipper</th>
            <th>Shipper Contact</th>
            <th>Consignee</th>
            <th>Consignee Contact</th>
            <th>Weight</th>
            <th>Pcs</th>
            <th>Rate</th>
            <th>Local</th>
            <th>Packing</th>
            <th>Total Amount</th>
            <th>Office</th>

          </tr>
        </thead>
        <tbody id="table-body">
          <?php
          $query = "SELECT s.shipment_id, s.date, s.origin, s.destination, s.receipt_no, s.shipper_name, s.shipper_contact, s.consignee_name, s.consignee_contact, 
       s.weight, s.pieces, s.mode_of_payment, s.rate, s.local_charges, s.packing, s.total_amount, s.office
FROM shipments s
JOIN users u ON s.origin = u.city
WHERE u.userid = ?
ORDER BY s.shipment_id DESC";
          
          $stmt = mysqli_prepare($con, $query);
          mysqli_stmt_bind_param($stmt, "i", $userid);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);

          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['shipment_id'] . "</td>";
            echo "<td>" . date("d-m-Y", strtotime($row['date'])) . "</td>";
            echo "<td>" . $row['origin'] . "</td>";
            echo "<td>" . $row['destination'] . "</td>";
            echo "<td>" . $row['receipt_no'] . "</td>";
            echo "<td>" . $row['shipper_name'] . "</td>";
            echo "<td>" . $row['shipper_contact'] . "</td>";
            echo "<td>" . $row['consignee_name'] . "</td>";
            echo "<td>" . $row['consignee_contact'] . "</td>";
            echo "<td>" . $row['weight'] . "</td>";
            echo "<td>" . $row['pieces'] . "</td>";
            echo "<td>" . $row['rate'] . "</td>";
            echo "<td>" . $row['local_charges'] . "</td>";
            echo "<td>" . $row['packing'] . "</td>";
            echo "<td>" . $row['total_amount'] . "</td>";
            echo "<td>" . $row['office'] . "</td>";

            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class="container mt-3">
      <div class="text-center" id="loading-icon" style="display: none;">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>

    <script src="export.js"></script>
  </body>
</html>
