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
        $subjectcode = mysqli_escape_string($conn, $_POST['subjectcode']);
        $description = mysqli_escape_string($conn, $_POST['description']);
        $abbrevation = mysqli_escape_string($conn, $_POST['abbrevation']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $teacherid = mysqli_escape_string($conn, $_POST['teacherid']);

        $sql = "INSERT INTO `subjects`(`subject_code`, `name`, `abbreviation`, `semester`, `branch`, `teacher_id`) VALUES ('$subjectcode','$description','$abbrevation','$semester','$branch','$teacherid')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Subject Added.
        </div>';
            header("location: subject_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: subject_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);

        $sql = "DELETE FROM `subjects` WHERE subject_code='$enroll'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Subject Deleted.
        </div>';
            header("location: subject_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: subject_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $subjectcode = mysqli_escape_string($conn, $_POST['subjectcode']);
        $description = mysqli_escape_string($conn, $_POST['description']);
        $abbrevation = mysqli_escape_string($conn, $_POST['abbrevation']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);
        $teacherid = mysqli_escape_string($conn, $_POST['teacherid']);

        $sql = "UPDATE `subjects` SET `name`='$description',`abbreviation`='$abbrevation',`semester`='$semester',`branch`='$branch',`teacher_id`='$teacherid' WHERE `subject_code`=$subjectcode";
       
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Subject Details Updated.
        </div>';
            header("location: subject_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: subject_details.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
