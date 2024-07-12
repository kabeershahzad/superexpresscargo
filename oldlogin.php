<?php
include('config.php');
session_start(); 


if(isset($_POST['login'])){
    $username=$_POST['email'];
    $password=$_POST['password'];
    $city;

    $s="select * from users where email='$username'&& password='$password'";

    $result=mysqli_query($con,$s);
    $num=mysqli_num_rows($result);
    
    if($num==1){
        $row = mysqli_fetch_assoc($result);
        $city=$row['city'];


        $role = $row['role']; //getting user role
        if($role==1){
            header('location:admin.html');
        }
        else if($role== 2){
            header('location:index.html');
    
}
    }
else{
    echo"Incorrect username or password.";    
}
        
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>
    <link rel="stylesheet" href="login.css">
    
  </head>
  <body>
  <div class="card">
  <h1>Login</h1>
  <form id="login" method="post" action="login.php">
    <label>E-mail address</label>
    <input type="email" name="email" data-validate="required email" placeholder="user@example.com" />
    <label>Password</label>
    <input type="password" data-validate="required" name="password" />
    <input type="submit" name="login" value="Login" />
  </form>
</div>

    <!-- Add a loading icon -->
    <!-- <div class="container mt-3">
      <div class="text-center" id="loading-icon">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div> -->

    <script src="login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
