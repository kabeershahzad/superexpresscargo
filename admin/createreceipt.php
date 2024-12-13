<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('location:login.php');
    exit();
}

$userid = $_SESSION['userid'];

// Retrieve user's city from database
$query = "SELECT city, office FROM users WHERE userid = '$userid'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
$userCity = $row['city'];
$userOffice = $row['office'];

$notification = '';

if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $receipt = $_POST['receipt'];
    $shipper_name = $_POST['shipper-name'];
    $consignee_name = $_POST['consignee-name'];
    $weight = $_POST['weight'];
    $pieces = $_POST['pcs'];
    $mode_of_payment = $_POST['mode-of-payment'];
    $rate = $_POST['rate'];
    $local_charges = $_POST['local-charges'];
    $packing = $_POST['packing'];
    $total_amount = $_POST['total-amount'];

    if (isset($_POST['no-number-checkbox'])) {
        $shipper_contact = '-';
    } else {
        $shipper_contact = $_POST['shipper-contact'];
    }

    if (isset($_POST['no-number-consignee-checkbox'])) {
        $consignee_contact = '-';
    } else {
        $consignee_contact = $_POST['consignee-contact'];
    }
    
    
    try {
        $stmt = "INSERT INTO shipments (date, origin, destination, receipt_no, shipper_name, shipper_contact, consignee_name, consignee_contact, weight, pieces, mode_of_payment, rate, local_charges, packing, total_amount, office, status) 
                 VALUES ('$date', '$origin', '$destination', '$receipt', '$shipper_name', '$shipper_contact', '$consignee_name', '$consignee_contact', '$weight', '$pieces', '$mode_of_payment', '$rate', '$local_charges', '$packing', '$total_amount', '$userOffice', 'DISPATCHED')";

        $result = mysqli_query($con, $stmt);

        if ($result) {
            $notification = 'Shipment created successfully!';
        } else {
            $notification = 'Error: Shipment not created!';
        }
        // Redirect with notification message
        header('Location: createreceipt.php?notification=' . urlencode($notification));
        exit();
    } catch (Exception $e) {
        $notification = 'Error: ' . $e->getMessage();
        header('Location: createreceipt.php?notification=' . urlencode($notification));
        exit();
    }
}

// Retrieve notification message if present
if (isset($_GET['notification'])) {
    $notification = urldecode($_GET['notification']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Dispatch | Super Express</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
    <style>
        input, option, select, input-group, .form-check-label {
            cursor: pointer;
        }
        label {
            font-weight: bold;
        }
        .input-group-append {
            font-weight: normal;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: none;
            margin-top: 50px;
        }
        h2{
            border-radius: 10px;
            padding: 5px;            
        }
        #date{
            font-weight: bold;

        }
        .origin{
            text-transform:uppercase;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Super Express Cargo Service</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
     <h2 class="text-center">DISPATCH</h2>
        <form id="myForm" method="post" action="createreceipt.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="date" name="date" required />
                    </div>
                    <div class="form-group">
                        <label for="origin">Origin:</label>
                        <input type="text" class="form-control fw-bold origin" id="origin" name="origin" value="<?php echo $userCity; ?>" readonly required />
                    </div>
                    <div class="form-group">
                        <label for="destination">Destination:</label>
                        <select class="form-control fw-bold" id="destination" name="destination" required>
                            <option value="">Select destination</option>
                            <?php
                            $destinationCities = array("Karachi","Faisalabad", "Lahore", "Rawalpindi", "Multan","Peshawar", "Gujranwala","Hyderabad", "Sialkot", "Gujrat", "Sarghoda", "Bahawalpur");
                            if (($key = array_search($userCity, $destinationCities)) !== false) {
                                unset($destinationCities[$key]);
                            }
                            foreach ($destinationCities as $city) {
                                echo "<option value='$city'>$city</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="receipt">Receipt #:</label>
                        <input type="text" class="form-control" id="receipt" name="receipt" readonly required />
                    </div>
                    <div class="form-group">
                        <label for="shipper-name">Shipper Name:</label>
                        <input type="text" class="form-control" id="shipper-name" name="shipper-name" required />
                    </div>
                    <div class="form-group">
                        <label for="shipper-contact">Shipper Contact:</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="shipper-contact" name="shipper-contact" required />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="checkbox" id="no-number-checkbox" name="no-number-checkbox" />
                                    <label for="no-number-checkbox" class="form-check-label">No Number</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="consignee-name">Consignee Name:</label>
                        <input type="text" class="form-control" id="consignee-name" name="consignee-name" required />
                    </div>
                    <div class="form-group">
                        <label for="consignee-contact">Consignee Contact:</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="consignee-contact" name="consignee-contact" required />
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <input type="checkbox" id="no-number-consignee-checkbox" name="no-number-consignee-checkbox" />
                                    <label for="no-number-consignee-checkbox" class="form-check-label">No Number</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="weight">Weight:</label>
                        <input type="number" class="form-control" id="weight" name="weight" required />
                    </div>
                    <div class="form-group">
                        <label for="pcs">Pcs:</label>
                        <input type="number" class="form-control" id="pcs" name="pcs" required />
                    </div>
                    <div class="form-group">
                        <label for="mode-of-payment">Mode of Payment:</label>
                        <select class="form-control" id="mode-of-payment" name="mode-of-payment" required>
                            <option value="">Select mode of payment</option>
                            <option value="COD">COD</option>
                            <option value="PAID">PAID</option>
                            <option value="A/C">A/C</option>
                            <option value="FOC">FOC</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rate">Rate:</label>
                        <input type="number" class="form-control" id="rate" name="rate" required />
                    </div>
                    <div class="form-group">
                        <label for="packing">Packing:</label>
                        <input type="number" value="0" class="form-control" id="packing" name="packing" required />
                    </div>
                    <div class="form-group">
                        <label for="local-charges">Local Charges:</label>
                        <input type="number" value="0" class="form-control" id="local-charges" name="local-charges" required />
                    </div>
                    <div class="form-group">
                        <label for="total-amount">Total Amount: <span class="text-success fst-italic">(auto calculate)</span></label>
                        <input type="number" class="form-control text-success fw-bold" id="total-amount" name="total-amount" required />
                    </div>
                    <br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block" name="submit">Create New Dispatch</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="notification" id="notification"></div>
    <script src="./createreceipt.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    const message = "<?php echo $notification; ?>";
    if (message) {
        notification.textContent = message;
        notification.style.display = 'block';
        setTimeout(function() {
            notification.style.display = 'none';
        }, 2000);
    }
});
    </script>
</body>
</html>
