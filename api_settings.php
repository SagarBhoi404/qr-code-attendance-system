<?php
// print_r($_POST);
require "conn.php";
session_start();

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $location = mysqli_escape_string($conn, $_POST['location']);
        $lat = mysqli_escape_string($conn, $_POST['lat']);
        $lon = mysqli_escape_string($conn, $_POST['lon']);
        $covarage = mysqli_escape_string($conn, $_POST['covarage']);
   
        $sql = "UPDATE `settings` SET `location`='$location',`lat`='$lat',`lon`='$lon',`covarage`='$covarage' WHERE `id`='1'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Settings Updated.
        </div>';
            header("location: settings.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: settings.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
