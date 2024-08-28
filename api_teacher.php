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

    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $id = mysqli_escape_string($conn, $_POST['id']);
        $teachername = mysqli_escape_string($conn, $_POST['teachername']);
        $education = mysqli_escape_string($conn, $_POST['education']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $designation = mysqli_escape_string($conn, $_POST['designation']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "INSERT INTO `teachers`(`name`, `education`, `designation`, `branch`, `password`) VALUES ('$teachername','$education','$designation','$branch','$password')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Teacher Added.
        </div>';
            header("location: teacher_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: teacher_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);

        $sql = "DELETE FROM `teachers` WHERE id='$enroll'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Teacher Deleted.
        </div>';
            header("location: teacher_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: teacher_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $id = mysqli_escape_string($conn, $_POST['id']);
        $teachername = mysqli_escape_string($conn, $_POST['teachername']);
        $education = mysqli_escape_string($conn, $_POST['education']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $designation = mysqli_escape_string($conn, $_POST['designation']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "UPDATE `teachers` SET `name`='$teachername',`education`='$education',`designation`='$designation',`branch`='$branch',`password`='$password' WHERE `id`='$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Teacher Details Updated.
        </div>';
            header("location: teacher_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: teacher_details.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
