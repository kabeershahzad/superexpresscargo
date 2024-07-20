<?php
include('admin_function.php');
check_admin();
include('config.php');


if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $role = $_POST['role'];
    $office = $_POST['office'];


    try{
        $sql = "INSERT INTO users (name, email, password, city, role,office) VALUES ('$name', '$email', '$password', '$city', '$role','$office')";
        $result = mysqli_query($con, $sql);
        if($result){
            echo "<script>alert('User added successfully!')</script>";
        } else {
            echo "<script>alert('User not added!')</script>";
        }
    } catch(Exception $e){
        echo "<script>alert('Error: " . $e->getMessage() . "')</script>";
    }
}

if(isset($_POST['delete'])){
    $userid = $_POST['userid'];
    $delete_sql = "DELETE FROM users WHERE userid='$userid'";
    $delete_result = mysqli_query($con, $delete_sql);
    if($delete_result){
        echo "<script>alert('User deleted successfully!')</script>";
    } else {
        echo "<script>alert('User not deleted!')</script>";
    }
}

// Fetch data from users table
$query = "SELECT * FROM users";
$results = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" type="image/x-icon" href="./images/super-express-cargo.ico">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <?php include('adminnav.php') ?>
    <div class="container">
        <h1>Add User</h1>
        <form id="addUserForm" method="post" action="users.php">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="office" class="form-label">Office</label>
                <input type="text" class="form-control" id="office" name="office" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="1" required>
                    <label class="form-check-label" for="roleAdmin">Admin</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="roleUser" value="2" required>
                    <label class="form-check-label" for="roleUser">User</label>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add User</button>
        </form>

        <!-- Display Users Data -->
        <h2 class="mt-5">User List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>City</th>
                    <th>Office</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><?php echo $row['userid']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['office']; ?></td>
                        <td><?php echo $row['role'] == 1 ? 'Admin' : 'User'; ?></td>
                        <td>
                            <form method="post" action="users.php" style="display:inline;">
                                <input type="hidden" name="userid" value="<?php echo $row['userid']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
