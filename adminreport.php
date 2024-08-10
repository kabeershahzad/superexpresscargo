<?php
include('config.php');
include('admin_function.php');
check_admin();

if (isset($_POST['delete'])) {
    $shipmentId = $_POST['shipment']; // Corrected to match the hidden input name
    $delete_sql = "DELETE FROM shipments WHERE shipment_id='$shipmentId'";
    $delete_result = mysqli_query($con, $delete_sql);
    if ($delete_result) {
        echo "<script>alert('Shipment deleted successfully!'); window.location.href='adminreport.php';</script>";
    } else {
        echo "<script>alert('Shipment not deleted!'); window.location.href='adminreport.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">

    <style>
        body {
            padding-top: 60px;
        }
        tr {
            cursor: pointer;
            font-size: 16px;
            font-weight: 500px;
        }
        /* Adjust the table row size */
        #myTable tbody tr {
            height: 70px; /* Adjust this value as needed */
        }

        /* Optional: Center align text vertically within table cells */
        #myTable tbody td {
            vertical-align: middle;
        }

        /* Adjust the column sizes */
         #myTable td {
            white-space: nowrap; /* Prevents text from wrapping */
        }
    </style>
</head>
<body>
    <?php include('./adminnav.php'); ?>

    <div class="container">
    <h2 class="text-center">DELIVERY</h2>

        <div>
            <button class="btn btn-success mt-5" id="export-btn">Export In Excel</button>
        </div>
        <div class="table-responsive mt-3">

        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Receipt#</th>
                    <th>Status</th>

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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                $query = "SELECT * from shipments ORDER BY shipment_id DESC";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                 while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php $statusClass = $row['status'] == 'DISPATCHED' ? 'bg-warning text-black' : 'bg-success text-white'; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['shipment_id']); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($row['date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['origin']); ?></td>
                        <td><?php echo htmlspecialchars($row['destination']); ?></td>
                        <td><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                        <td class="<?php echo $statusClass; ?> text-center">
                            <span style="font-weight:bold"><?php echo htmlspecialchars($row['status']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['shipper_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['shipper_contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['consignee_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['consignee_contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['weight']); ?></td>
                        <td><?php echo htmlspecialchars($row['pieces']); ?></td>
                        <td><?php echo htmlspecialchars($row['rate']); ?></td>
                        <td><?php echo htmlspecialchars($row['local_charges']); ?></td>
                        <td><?php echo htmlspecialchars($row['packing']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($row['office']); ?></td>
                        <td>
                            <form method="post" action="adminreport.php" style="display:inline;" onsubmit="event.stopPropagation();">
                                <input type="hidden" name="shipment" value="<?php echo htmlspecialchars($row['shipment_id']); ?>">
                                <button type="submit" name="delete" class="btn btn-danger" onclick="event.stopPropagation();">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                
            </tbody>
        </table>
        </div>
    </div>
    <div class="container mt-3">
        <div class="text-center" id="loading-icon" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
        
  });
  $("#myTable").DataTable({
    order: [[0, "desc"]], // Sorting by the first column (ID) in descending order
  });

  // Add click event listener to visible rows
  $("#myTable tbody").on("click", "tr", function () {
    var shipmentId = $(this).find("td").eq(4).text();
    var url = "print_invoice.php?id=" + shipmentId;
    window.open(url, "_blank");
  });

  document.getElementById("export-btn").addEventListener("click", function () {
    var visibleRows = Array.from(
      document.querySelectorAll("#myTable tbody tr")
    ).filter(function (row) {
      return row.style.display !== "none";
    });
    var filteredTable = document.createElement("table");
    filteredTable.innerHTML = document.getElementById("myTable").innerHTML;
    var existingTBody = filteredTable.querySelector("tbody");
    if (existingTBody) {
      filteredTable.removeChild(existingTBody);
    }
    var newTBody = document.createElement("tbody");
    visibleRows.forEach(function (row) {
      newTBody.appendChild(row.cloneNode(true));
    });
    filteredTable.appendChild(newTBody);
    var wb = XLSX.utils.table_to_book(filteredTable);
    var wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
    var blob = new Blob([wbout], { type: "application/octet-stream" });
    saveAs(blob, "table_data.xlsx");
  });


    </script>
</body>
</html>
