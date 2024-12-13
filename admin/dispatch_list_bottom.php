<?php
// session_start(); // Uncomment this line
include ('config.php');

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
    <title>Dispatch | Super Express</title>
    <link href="./DataTables/datatables.min.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />


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
            height: 70px;
            /* Adjust this value as needed */
        }

        /* Optional: Center align text vertically within table cells */
        #myTable tbody td {
            vertical-align: middle;
        }

        /* Adjust the column sizes */
        #myTable td {
            white-space: nowrap;
            /* Prevents text from wrapping */

        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Super Express Cargo Service</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./createreceipt.php">Create New Dispatch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./dispatch_list_bottom.php">Dispatch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./report.php">Delivery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contacts.php">Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <!-- Add more menu items for the admin panel -->
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">

        <div>
            <button class="btn btn-success mt-5" id="export-btn">Export to Excel</button>

        </div>
        <div class="table-responsive mt-3">

            <table class="table table-striped" id="myTable">
                <thead>
                    <tr class="text-center">
                        <th>Id</th>
                        <th>Action</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Receipt#</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Shipper</th>
                        <th>Shipper Contact</th>
                        <th>Consignee</th>
                        <th>Consignee Contact</th>
                        <th>Weight</th>
                        <th>Pcs</th>
                        <th>Mode of Payment</th>
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
                s.weight, s.pieces, s.mode_of_payment,s.status ,s.rate, s.local_charges, s.packing, s.total_amount, s.office
                FROM shipments s
                JOIN users u ON s.origin = u.city
                WHERE u.userid = ?
                ORDER BY s.shipment_id DESC";

                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "i", $userid);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($result)):
                        $statusClass = $row['status'] == 'DISPATCHED' ? 'bg-warning text-black' : 'bg-success text-white'; ?>

                        <tr>
                            <td class=''><?php echo htmlspecialchars($row['shipment_id']); ?></td>
                            <td> <button type="button" class="btn btn-danger delete-btn"
                                    data-id="<?php echo htmlspecialchars($row['shipment_id']); ?>">
                                    Delete
                                </button></td>
                            <td><?php echo htmlspecialchars($row['origin']); ?></td>
                            <td><?php echo htmlspecialchars($row['destination']); ?></td>
                            <td><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                            <td class='fw-bold'><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
                            <td class='<?php echo $statusClass; ?> text-center'><span
                                    style='font-weight:bold'><?php echo htmlspecialchars($row['status']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['shipper_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['shipper_contact']); ?></td>
                            <td><?php echo htmlspecialchars($row['consignee_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['consignee_contact']); ?></td>
                            <td><?php echo htmlspecialchars($row['weight']); ?></td>
                            <td><?php echo htmlspecialchars($row['pieces']); ?></td>
                            <td><?php echo htmlspecialchars($row['mode_of_payment']); ?></td>

                            <td><?php echo htmlspecialchars($row['rate']); ?></td>
                            <td><?php echo htmlspecialchars($row['local_charges']); ?></td>
                            <td><?php echo htmlspecialchars($row['packing']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['office']); ?></td>
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
    
    <script src="./DataTables/datatables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    
    <script>
        $(document).ready(function () {
            const deliverButtons = document.querySelectorAll(".delete-btn");
            deliverButtons.forEach((button) => {
                button.addEventListener("click", function (event) {
                    event.stopPropagation();
                    const shipmentId = this.getAttribute("data-id");
                    if (
                        confirm("Are you sure you want to delete this shipment?")
                    ) {
                        fetch("delete_shipment.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: new URLSearchParams({ shipment_id: shipmentId }),
                        })
                            .then((response) => response.text())
                            .then((text) => {
                                alert(text);
                                location.reload();
                            })
                            .catch((error) => alert("An error occurred: " + error));
                    }
                });
            });
            $('#myTable').DataTable({
                "order": [[0, 'desc']], // Sorting by the first column (ID) in descending order
                

            });
            // Custom search function for specific columns


            // Add click event listener to visible rows
            $('#myTable tbody').on('click', 'tr', function () {
                var shipmentId = $(this).find('td').eq(4).text();
                var url = "print_invoice.php?id=" + shipmentId;
                window.open(url, "_blank");
            });

            document.getElementById("export-btn").addEventListener("click", function () {
    // Get visible rows only
    var visibleRows = Array.from(document.querySelectorAll("#myTable tbody tr"))
        .filter(row => row.style.display !== "none")
        .map(row => Array.from(row.querySelectorAll("td")).map(cell => cell.innerText));

    // Add title 'Dispatch' as the first row
    visibleRows.unshift(["Dispatch"]);

    // Create a worksheet from visible rows
    var ws = XLSX.utils.aoa_to_sheet(visibleRows);

    // Set column widths to auto-fit
    ws['!cols'] = visibleRows[0].map((_, i) => ({
        wch: Math.max(...visibleRows.map(row => (row[i] || '').length))
    }));

    // Create workbook and append the worksheet
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Dispatch Data");

    // Export to Excel
    var wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
    saveAs(new Blob([wbout], { type: "application/octet-stream" }), "Dispatch Data.xlsx");
});
        });
    </script>
</body>

</html>