<?php
  include('admin_function.php');
  check_admin();
  include('config.php');
  // Assuming you have a database connection in 'admin_function.php'
$totalPendingDeliveries = 0;
$totalcodfromkhi=0;

$sql = "SELECT COUNT(*) AS total FROM shipments WHERE status = 'dispatched'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalPendingDeliveries = $row['total'];
}
$sql = "SELECT SUM(total_amount) AS totalcod 
        FROM shipments 
        WHERE origin = 'karachi' 
        AND mode_of_payment = 'COD'
        AND MONTH(date) = MONTH(CURDATE()) 
        AND YEAR(date) = YEAR(CURDATE())";
        
        $resultcod = $con->query($sql);

if ($resultcod) {
    $row = $resultcod->fetch_assoc();
    // Check if 'totalcod' is NULL and set to 0 if true
    $totalcodfromkhi = isset($row['totalcod']) ? $row['totalcod'] : 0;
    // If the result is NULL (no matching rows), set $totalcodfromkhi to 0
    if ($totalcodfromkhi === NULL) {
        $totalcodfromkhi = 0;
    }
} else {
    // Handle query error
    die("Error executing query: " . $con->error);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel Super Express</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">
    <style>
      /* Custom styles for the admin panel */
      body {
        padding-top: 100px;
        
      }
      .dashboard-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 200px; /* Adjust as needed */
      }
      .dashboard-card h2 {
        font-size: 2rem;
        margin-bottom: 10px;
        color: black;
      }
      .dashboard-card p {
        font-size: 1.5rem;
        color:maroon;
        font-weight: 700;
      }
      .dashboard-card p.cod{
        color: seagreen;
      }
      
    </style>
  </head>
  <body>
    <?php include ('adminnav.php'); ?>

    <div class="container d-flex flex-column align-items-center">
  <!-- Heading at the top -->
  <div class="row">
    <div class="col text-center">
      <h1 class="my-4">Welcome to the Admin Panel</h1>
    </div>
  </div>

  <!-- Dashboard Card for Pending Deliveries -->
  <div class="row justify-content-center">
    <!-- Card for Total Pending Deliveries -->
    <div class="col-lg-4 col-md-6 mb-4">
      <a href="adminreport.php" class="text-decoration-none">
        <div class="dashboard-card text-center">
          <h2>Total Pending Deliveries</h2>
          <p><?php echo $totalPendingDeliveries; ?></p>
        </div>
      </a>
    </div>

    <!-- Card for Total COD From Karachi -->
    <div class="col-lg-4 col-md-6 mb-4">
      <a href="adminreport.php" class="text-decoration-none">
        <div class="dashboard-card text-center">
          <h2>Total COD From Karachi</h2>
          <p class="cod"><?php echo $totalcodfromkhi; ?></p>
        </div>
      </a>
    </div>
  </div>
  <div class="button-container">
        <a href="./users.php"
          ><button class="btn btn-success">Users</button></a
        >
        <a href="./adminreport.php"
          ><button class="btn btn-primary">Delivery</button></a
        >
       
        <a href="./logout.php"
          ><button class="btn btn-danger">Logout</button></a
        >
      </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>