<?php
include('./config.php');
// Start session at the very beginning

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('location:login.php');
    exit();
}

$userid = $_SESSION['userid'];
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | Super Express</title>
    <style>
        .container {
            margin-top: 100px;
        }
        /*h2 {*/
        /*    color: white;*/
        /*    border-radius: 10px;*/
        /*    padding: 5px;*/
        /*}*/
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
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
    <div class="container mt-3">

    <h2 class="text-center">CUSTOMER</h2>

        <div class="table-responsive mt-3">

            <table class="table table-striped" id="myTable">
                <thead>
                    <tr class="text-center">
                        <th>Customer Name</th>
                        <th>Phone #</th>


                    </tr>
                </thead>
                <tbody id="mtTable">
                    <?php
                    $query = "SELECT DISTINCT
                                'Shipper' AS customer_type,
                                s.shipper_name AS name,
                                s.shipper_contact AS contact
                                FROM 
                                shipments s
                                WHERE 
                                s.shipper_name <> 'self'
                                UNION
                                SELECT DISTINCT
                                'Consignee' AS customer_type,
                                s.consignee_name AS name,
                                s.consignee_contact AS contact
                                FROM 
                                shipments s
                                WHERE 
                                s.consignee_name <> 'self'
                                ORDER BY 
                                customer_type, name;";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['contact']; ?></td>
                        </tr>
                    <?php endwhile;
                    ?>
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
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
</body>

</html>