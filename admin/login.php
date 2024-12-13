<?php
include('config.php');
//session_start(); 
// if (session_status() == PHP_SESSION_NONE) {
//   session_start();
// }

if(isset($_POST['login'])){
    $username = $_POST['email'];
    $password = $_POST['password'];

    $s = "SELECT * FROM users WHERE email='$username' AND password='$password'";

    $result = mysqli_query($con, $s);
    $num = mysqli_num_rows($result);
    
    if($num == 1){
        $row = mysqli_fetch_assoc($result);

        // Store user details in the session
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['city'] = $row['city'];
        $_SESSION['role'] = $row['role']; // Store user role in the session
        $_SESSION['office'] = $row['office']; // Store user role in the session

        if($row['role'] == 1){
            header('location:admin.php');
        } else if($row['role'] == 2){
            header('location:index.php');
        }
        exit();
    } else {
      $error_message = "Incorrect email or password.";    
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Super Express</title>
    <!-- Bootstrap CSS -->
    <link
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">

    <style>
      body,
      html {
        height: 100%;
        position: relative;
        margin: 0;
        font-family: "Montserrat", sans-serif;
        background-image: url("./images/bg.webp"); /* Update with your image path */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100vw;
        margin: 0;

      }
      .error-message {
      color: red;
      font-size: 0.9rem;
      margin-top: 10px;
    }

      .bg {
        height:100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
      }
      .login-container {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      label {
            text-align: left; /* Align labels to the left */
            display: block; /* Make labels block elements */
        }
      .login-card {
        width: 100%;
        max-width: 400px;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: white;
      }
      .logo {
          max-width: 150px;
            margin: 20px auto;
        margin-bottom: 1rem; /* Add bottom margin */
      }
      .form-control {
        width: 100%; /* Ensures the input fields take up the full width of the card */
      }
      .card-title {
        font-weight: bold; /* Make the login title bold */
      }
      label {
        text-align: left; /* Align labels to the left */

      }
      .footer {
        position: absolute;
        bottom: 10px;
        right: 20px;
        font-size: 1.2rem;
        color: white;      }
      h1{
        color:white;

        
      }
    </style>
  </head>
  <body>
    <div class="bg">
      <div class="container login-container">
        <div class="text-center">
          <img src="./images/super-express-cargo.png" alt="Logo" class="logo" />
          <!-- Replace with your logo URL -->
          <h1>Super Express Cargo</h1>
          <div class="card login-card">
            <div class="card-body">
              <h5 class="card-title text-center">Office Login</h5>
              <br>
              <form id="login" method="post" action="login.php">
                <div class="form-group">
                  <label for="email">Email address</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    placeholder="Enter email"
                    name="email"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password"
                    placeholder="Password"
                    name="password"
                    required
                  />
                </div>
                <?php if (!empty($error_message)): ?>
                <div class="error-message">
                  <?php echo $error_message; ?>
                </div>
              <?php endif; ?>
              
                <br>
                <button
                  type="submit"
                  name="login"
                  class="btn btn-primary btn-block"
                >
                  Login
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      
    <!-- Bootstrap JS and dependencies -->
    <!-- <script src="login.js"></script> -->
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
