<?php
include('config.php');

function check_admin() {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
        return true;
    } else {
        header('location:unauthorized.php');
        exit();
    }
}
?>