<?php
include('./admin_function.php');
check_admin();
include('./config.php');

// Get the current month and year by default
$currentMonthYear = date('Y-m');

// Get the selected month, defaulting to the current month
$selectedMonth = $_POST['month'] ?? $currentMonthYear;
$selectedCity = $_POST['city'] ?? 'overall';

// Extract year and month from the selected month
$selectedYear = date('Y', strtotime($selectedMonth));
$selectedMonthNum = date('m', strtotime($selectedMonth));

$totalcodfromkhi = 0;

// Fetch distinct destination cities based on the selected month
$cities = [];
$cityQuery = "SELECT DISTINCT destination 
              FROM shipments 
              WHERE origin = 'karachi' 
              AND MONTH(date) = ? 
              AND YEAR(date) = ? 
              ORDER BY destination";
$cityStmt = $con->prepare($cityQuery);
$cityStmt->bind_param('ii', $selectedMonthNum, $selectedYear);
$cityStmt->execute();
$cityResult = $cityStmt->get_result();

while ($row = $cityResult->fetch_assoc()) {
    $cities[] = $row['destination'];
}

// Get the total COD amount based on the selected city and month
if ($selectedCity === 'overall') {
    // Sum COD from Karachi to all cities for the selected month and year
    $sql = "SELECT SUM(total_amount) AS totalcod 
            FROM shipments 
            WHERE origin = 'karachi' 
            AND mode_of_payment = 'COD'
            AND MONTH(date) = ? 
            AND YEAR(date) = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $selectedMonthNum, $selectedYear);
} else {
    // Sum COD for the selected destination city
    $sql = "SELECT SUM(total_amount) AS totalcod 
            FROM shipments 
            WHERE origin = 'karachi'
            AND destination = ? 
            AND mode_of_payment = 'COD'
            AND MONTH(date) = ? 
            AND YEAR(date) = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('sii', $selectedCity, $selectedMonthNum, $selectedYear);
}

$stmt->execute();
$resultcod = $stmt->get_result();

if ($resultcod && $resultcod->num_rows > 0) {
    $row = $resultcod->fetch_assoc();
    $totalcodfromkhi = $row['totalcod'] ?? 0;
}

$currentMonth = date('F', strtotime($selectedMonth));
$currentYear = date('Y', strtotime($selectedMonth));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Super Express</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
          crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">
    <style>
        /* Custom styles for the admin panel */
        body {
            padding-top: 50px;
            background-color: #f4f4f9;
            color: #333;
        }

        .dashboard-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            transition: transform 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #444;
        }

        .dashboard-card p {
            font-size: 1.8rem;
            color: #555;
            font-weight: 700;
        }

        .dashboard-card p.cod {
            color: seagreen;
        }

        .container {
            max-width: 900px;
        }

        .heading-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading-section h1 {
            font-size: 2.5rem;
            color: #333;
            font-weight: bold;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            margin: 0 10px;
        }
    </style>
</head>
<body>
<?php include('adminnav.php'); ?>

<div class="container mt-3">
    <!-- Dynamic Heading -->
    <div class="heading-section">
        <h1>COD of <?php echo $currentMonth . ' ' . $currentYear; ?></h1>
    </div>

    <!-- Month and City Selection Form -->
    <form method="POST" action="">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="month">Select Month:</label>
                    <input type="month" id="month" name="month" value="<?php echo htmlspecialchars($selectedMonth); ?>"
                           class="form-control" onchange="updateCities()">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="city">Select Destination City:</label>
                    <select id="city" name="city" class="form-select" onchange="this.form.submit()">
                        <option value="overall" <?php echo ($selectedCity === 'overall') ? 'selected' : ''; ?>>Overall</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo htmlspecialchars($city); ?>" <?php echo ($selectedCity === $city) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($city); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Dashboard Card for COD -->
    <div class="row justify-content-center">
    <!-- Card for Total COD -->
        <div class="col-lg-6 col-md-12 mb-4 mt-3">
            <a href="adminreport.php" class="text-decoration-none">
                <div class="dashboard-card">
                    <h2>Total COD <?php echo ($selectedCity === 'overall') ? 'From Karachi' : 'From Karachi to ' . ucfirst($selectedCity); ?></h2>
                    <p class="cod"><?php echo 'Rs ' . number_format($totalcodfromkhi); ?></p>
                </div>
            </a>
        </div>
    </div>

    <!-- Buttons -->
    <div class="button-container">
        <a href="./users.php"><button class="btn btn-success">Users</button></a>
        <a href="./adminreport.php"><button class="btn btn-primary">Delivery</button></a>
        <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateCities() {
        // Submit the form to update the cities based on the selected month
        document.forms[0].submit();
    }
</script>
</body>
</html>
