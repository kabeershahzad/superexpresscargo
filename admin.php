<?php
  include('admin_function.php');
  check_admin();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <style>
      /* Custom styles for the admin panel */
      body {
        padding-top: 60px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
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
              <a class="nav-link" href="./createReceipt.php">Create Receipt</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./users.php">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="report.php">Report</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">logout</a>
            </li>
            <!-- Add more menu items for the admin panel -->
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <!-- Content of the admin panel goes here -->
      <h1>Welcome to the Admin Panel</h1>
      <p>This is the dashboard of the admin panel.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
