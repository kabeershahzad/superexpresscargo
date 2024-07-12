<?php
  include('admin_function.php');
  check_admin();
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
    <style>
      /* Custom styles for the admin panel */
      body {
        padding-top: 60px;
      }
    </style>
  </head>
  <body>
    <?php include ('adminnav.php') ?>

    <div class="container">
      <!-- Content of the admin panel goes here -->
      <h1>Welcome to the Admin Panel</h1>
      <p>This is the dashboard of the admin panel.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
