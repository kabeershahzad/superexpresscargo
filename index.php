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
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <style>
      body {
        background-image: url("./containers.jpg"); /* Update with your image path */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
        width: 100vw;
      }
      .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: rgba(0,0,0,0);
        /*background-color: rgba(255, 255, 255, 0.8); /* Add a white background with transparency */
        border-radius: 10px;
        padding: 20px;
      }

      .center {
        text-align: center;
      }

      .btn {
        margin: 5px; /* Add some spacing between buttons */
      }
    </style>
    <link rel="stylesheet" href="style.css" />
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
          </ul>
        </div>
      </div>
    </nav>

    <div class="container" style="color:white;">
      <h1 class="center" style="color:white;">Welcome to the Super Express</h1>
      <h2 class="center">
        Hello,
        <?php echo htmlspecialchars($name); ?>!
      </h2>
      <h2 class="center">
        Your User ID:
        <?php echo htmlspecialchars($userid); ?>
      </h2>
      <h2 class="center">
        Your City:
        <?php echo htmlspecialchars($city); ?>
      </h2>
      <div class="button-container">
        <a href="./createreceipt.php"
          ><button class="btn btn-success">Dispatch</button></a
        >
        <a href="./createreceipt.php"
          ><button class="btn btn-primary">Delivery</button></a
        >
        <a href="./report.php"
          ><button class="btn btn-warning">Report</button></a
        >
        <a href="./logout.php"
          ><button class="btn btn-danger">Logout</button></a
        >
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
