<?php
include('config.php');
//session_start();

// Check if user is logged in
if(!isset($_SESSION['userid'])){
    header('location:login.php');
    exit();
}

$userid = $_SESSION['userid'];
$name = $_SESSION['name'];
$city = $_SESSION['city'];
$office = $_SESSION['office'];
// Query to get the total pending deliveries
$query = "SELECT COUNT(s.shipment_id) as total_pending 
          FROM shipments s
          JOIN users u ON s.destination = u.city
          WHERE u.userid = ? AND s.status = 'dispatched'";
          
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_pending = $row['total_pending'];


$sql = $con->prepare("SELECT SUM(s.total_amount) AS totalcod 
                      FROM shipments s
                      JOIN users u ON s.origin = u.city
                      WHERE u.userid = ? 
                      AND s.mode_of_payment = 'COD'
                      AND MONTH(s.date) = MONTH(CURDATE()) 
                      AND YEAR(s.date) = YEAR(CURDATE())");

if ($sql === false) {
    die('Prepare failed: ' . htmlspecialchars($con->error));
}

$userId = intval($userid); // Ensure $userId is an integer
$sql->bind_param("i", $userid);

if (!$sql->execute()) {
    die('Execute failed: ' . htmlspecialchars($sql->error));
}

$result = $sql->get_result();

if ($result === false) {
    die('Get result failed: ' . htmlspecialchars($sql->error));
}

$row = $result->fetch_assoc();
$totalCod = $row['totalcod'] !== null ? $row['totalcod'] : 0;



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | Super Express</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">

    <style>
      .dashboard-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            transition: transform 0.3s;
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
      .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
      .dashboard-card p.cod{
        color: seagreen;
      }
      @media (max-width: 767px) {
            .container {
                padding: 10px;
            }
            h1 {
                font-size: 1.5rem; /* Decrease the size of h1 */
                margin-top: 20px;
            }
            h2 {
                font-size: 1.2rem; /* Decrease the size of h2 */
            }
            .dashboard-card {
                padding: 10px;
            }
            .button-container {
                flex-direction: column;
            }
           
        }
    </style>
    <link rel="stylesheet" href="index.css" />
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Super Express Cargo Service</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
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
          </ul>
        </div>
      </div>
    </nav>

    <div class="container" style="color:white;">
      <h1 class="center" style="color:white;">Welcome to Super Express</h1>
      <h2 class="center">
        Hello 
        <?php echo htmlspecialchars($name); ?>
      </h2>
      
      <h2 class="center">
        Station:
        <?php echo htmlspecialchars($office); ?>
      </h2>
      
      
      <!-- Dashboard Card for Pending Deliveries -->
  <div class="row justify-content-center">
    <!-- Card for Total Pending Deliveries -->
    <div class="col-lg-4 col-md-6 mb-4">
      <a href="./report.php" class="text-decoration-none">
        <div class="dashboard-card text-center">
          <h2>Pending Deliveries</h2>
          <p><?php echo $total_pending; ?></p>
        </div>
      </a>
    </div>

    <!-- Card for Total COD From Karachi -->
    <div class="col-lg-4 col-md-6 mb-4">
      <a href="./dispatch_list_bottom.php" class="text-decoration-none">
        <div class="dashboard-card text-center">
          <h2>COD from <?php echo $city?> </h2>
          <p class="cod"><?php echo'Rs '.number_format( $totalCod); ?></p>
        </div>
      </a>
    </div>
  </div>
      <div class="button-container">
        <a href="./dispatch_list_bottom.php"
          ><button class="btn btn-success">Dispatch</button></a
        >
        <a href="./report.php"
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
