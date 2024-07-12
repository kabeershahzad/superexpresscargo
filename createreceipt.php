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


if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $receipt = $_POST['receipt'];
    $shipper_name = $_POST['shipper-name'];
    $shipper_contact = $_POST['shipper-contact'];
    $consignee_name = $_POST['consignee-name'];
    $consignee_contact = $_POST['consignee-contact'];
    $weight = $_POST['weight'];
    $pieces = $_POST['pcs'];
    $mode_of_payment = $_POST['mode-of-payment'];
    $rate = $_POST['rate'];
    $local_charges = $_POST['local-charges'];
    $packing = $_POST['packing'];
    $total_amount = $_POST['total-amount'];
    $office=$_POST['office'];

    // Prepare and execute the SQL statement
    try {
        $stmt = "INSERT INTO shipments (date, origin, destination, receipt_no, shipper_name, shipper_contact, consignee_name, consignee_contact, weight, pieces, mode_of_payment, rate, local_charges, packing, total_amount, office) 
                 VALUES ('$date', '$origin', '$destination', '$receipt', '$shipper_name', '$shipper_contact', '$consignee_name', '$consignee_contact', '$weight', '$pieces', '$mode_of_payment', '$rate', '$local_charges', '$packing', '$total_amount','$office')";

        $result = mysqli_query($con, $stmt);

        if ($result) {
            echo "<script>alert('Shipment created successfully!');</script>";
        } else {
            echo "<script>alert('Error: Shipment not created!');</script>";
            echo "Error: " . mysqli_error($con);  // Added for debugging
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the submitted date in a variable
    $submittedDate = $_POST['date'];
} else {
    // Default date is today's date
    $submittedDate = date('Y-m-d');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create New Dispatch</title>
    <script src="https://www.gstatic.com/firebasejs/9.8.4/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.8.4/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.8.4/firebase-auth-compat.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">Super Express Cargo Service</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./createreceipt.php">Dispatch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./createreceipt.php">Delivery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./report.php">Report</a>
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
        <h1>Create New Dispatch</h1>
        <form id="shipment-form" method="post" action="createreceipt.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo $submittedDate; ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="origin">Origin:</label>
                        <input type="text" class="form-control" id="origin" name="origin" value="<?php echo $userCity; ?>"
                            readonly required />
                    </div>
                    <div class="form-group">
                        <label for="office">Office:</label>
                        <input type="text" class="form-control" id="office" name="office" value="<?php echo $userOffice; ?>"
                            readonly required />
                    </div>
                    <div class="form-group">
                        <label for="destination">Destination:</label>
                        <select class="form-control" id="destination" name="destination" required>
                            <option value="">Select destination</option>
                            <?php
                            // Array of destination cities (modify as per your actual cities)
                            $destinationCities = array("Faisalabad", "Karachi", "Lahore");

                            // Remove $userCity from $destinationCities array
                            if (($key = array_search($userCity, $destinationCities)) !== false) {
                                unset($destinationCities[$key]);
                            }

                            // Generate options for each destination city
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
                                    <input type="checkbox" id="no-number-checkbox" />
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
                                    <input type="checkbox" id="no-number-checkbox2" />
                                    <label for="no-number-checkbox2" class="form-check-label">No Number</label>
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rate">Rate:</label>
                        <input type="number" class="form-control" id="rate" name="rate" required />
                    </div>
                    <div class="form-group">
                        <label for="local-charges">Local Charges:</label>
                        <input type="number" class="form-control" id="local-charges" name="local-charges" required />
                    </div>
                    <div class="form-group">
                        <label for="packing">Packing:</label>
                        <input type="number" class="form-control" id="packing" name="packing" required />
                    </div>
                    <div class="form-group">
                        <label for="total-amount">Total Amount:</label>
                        <input type="number" class="form-control" id="total-amount" name="total-amount" required />
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-wide" name="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        <?php include('dispatch_list_bottom.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const weightInput = document.getElementById("weight");
            const rateInput = document.getElementById("rate");
            const localChargesInput = document.getElementById("local-charges");
            const packingInput = document.getElementById("packing");
            const totalAmountInput = document.getElementById("total-amount");

            // Add event listeners to the necessary fields
            weightInput.addEventListener("input", calculateTotalAmount);
            rateInput.addEventListener("input", calculateTotalAmount);
            localChargesInput.addEventListener("input", calculateTotalAmount);
            packingInput.addEventListener("input", calculateTotalAmount);

            function calculateTotalAmount() {
                const weight = parseFloat(weightInput.value) || 0;
                const rate = parseFloat(rateInput.value) || 0;
                const localCharges = parseFloat(localChargesInput.value) || 0;
                const packing = parseFloat(packingInput.value) || 0;

                const totalAmount = weight * rate + localCharges + packing;
                totalAmountInput.value = totalAmount.toFixed(0);
            }
        });

        const destinationSelect = document.getElementById("destination");
        const receiptInput = document.getElementById("receipt");

        destinationSelect.addEventListener("change", updateReceiptNumber);

        function updateReceiptNumber() {
            const destinationValue = destinationSelect.value;
            const randomNumber = generateRandomNumber(7);

            // Format the receipt number as "SUPER-random-Destination"
            const receiptNumber = `SUPER-${randomNumber}-${destinationValue}`;

            receiptInput.value = receiptNumber;
        }

        function generateRandomNumber(digits) {
            const min = Math.pow(10, digits - 1);
            const max = Math.pow(10, digits) - 1;
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        document.addEventListener("DOMContentLoaded", function () {
            const shipperContactInput = document.getElementById("shipper-contact");
            const noNumberCheckbox = document.getElementById("no-number-checkbox");
            const consigneeContactInput = document.getElementById("consignee-contact");
            const noNumberCheckbox2 = document.getElementById("no-number-checkbox2");

            // Add event listeners to the checkboxes
            noNumberCheckbox.addEventListener("change", toggleShipperContactInput);
            noNumberCheckbox2.addEventListener("change", toggleConsigneeContactInput);

            function toggleShipperContactInput() {
                shipperContactInput.value = noNumberCheckbox.checked ? "-" : "";
                shipperContactInput.disabled = noNumberCheckbox.checked;
                shipperContactInput.required = !noNumberCheckbox.checked;
            }

            function toggleConsigneeContactInput() {
                consigneeContactInput.value = noNumberCheckbox2.checked ? "-" : "";
                consigneeContactInput.disabled = noNumberCheckbox2.checked;
                consigneeContactInput.required = !noNumberCheckbox2.checked;
            }
        });
    </script>
</body>

</html>
