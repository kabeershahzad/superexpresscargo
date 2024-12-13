<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | Super Express</title>
    <link rel="stylesheet" href="./index.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="icon" type="image/x-icon" href="./admin/images/super-express-cargo.ico">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2626132194400732"
     crossorigin="anonymous"></script>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            flex: 1;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .logo {
            max-width: 150px;
            margin: 20px auto;
        }

        .table {
            background-color: #f9f9f9;
        }

        .status-dispatched {
            color: #ffc107;
        }

        .status-delivered {
            color: #28a745;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <img src="./admin/images/super-express-cargo.png" alt="Super Express Cargo Logo" class="logo" />
        <h1 class="display-6 mb-4">Super Express Cargo</h1>
        <form class="mt-4" method="post" action="index.php">
            <div class="form-group mb-3">
                <label for="cnNumber" class="form-label">Track Your Shipment</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="cnNumber" name="cnNumber"
                        placeholder="Enter Receipt Number" autofocus />
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Track Shipment</button>
        </form>

        <div>
            <?php include './admin/config.php'; ?>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <?php
                $cnNumber = $_POST['cnNumber'];
                $stmt = $con->prepare("SELECT date,status, shipper_name, consignee_name FROM shipments WHERE receipt_no = ?");
                $stmt->bind_param("s", $cnNumber);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($date, $status, $shipperName, $consigneeName);
                    $stmt->fetch();
                    $statusClass = ($status === 'DISPATCHED') ? 'status-dispatched' : (($status === 'DELIVERED') ? 'status-delivered' : '');
                ?>
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Receipt No:</th>
                                    <td class="fw-bold receipt_value"><?php echo htmlspecialchars($cnNumber); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Dispatch Date:</th>
                                    <td class="fw-bold receipt_value"><?php echo htmlspecialchars(date('d-m-Y', strtotime($date))); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Status:</th>
                                    <td class="fw-bold <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Consignee:</th>
                                    <td><?php echo htmlspecialchars($consigneeName); ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Shipper:</th>
                                    <td><?php echo htmlspecialchars($shipperName); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                } else {
                    echo '<div class="alert alert-danger mt-4" role="alert">';
                    echo 'No records found for CN Number ' . htmlspecialchars($cnNumber);
                    echo '</div>';
                }

                $stmt->close();
                $con->close();
                ?>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer mt-5">
        <p>Main Karachi Office: G-56, Deans Market, Main Tariq Road, Karachi</p>
        <p>Contact: 0321-9285851, 0321-8756687</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>
