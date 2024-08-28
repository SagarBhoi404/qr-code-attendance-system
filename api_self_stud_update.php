<?php
require "conn.php";
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true && $_SESSION['usertype'] == 'STUDENT') {


    $enrollmentno = $_SESSION['enrollment_no'];
    $studentname = mysqli_escape_string($conn, $_POST['studentname']);
    $filename = $_FILES['pic']['name'];

    if ($_FILES['pic']['error'] != UPLOAD_ERR_NO_FILE) {

        // File upload
        $uploadDir = 'img/profile/';
        $uploadFile = $uploadDir . $filename;

        // Check file extension
        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['jpg', 'png', 'jpeg'])) {
            die('Invalid file type. Please upload jpg,png,jpeg.');
        }

        $filename = time() . "." . $fileExtension;
        $uploadFile = $uploadDir . $filename;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {

            $sql = "UPDATE `students` SET `name`='$studentname',`pic`='$filename' WHERE `enrollment_no`='$enrollmentno'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Student Details Updated.
        </div>';
                header("location: profile_stud.php");
                exit();
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
                header("location: profile_stud.php");
                exit();
            }
        } else {
            die('File upload failed.');
        }
    } else {

        $sql = "UPDATE `students` SET `name`='$studentname' WHERE `enrollment_no`='$enrollmentno'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Student Details Updated.
        </div>';
            header("location: profile_stud.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Something went wrong!.
        </div>';
            header("location: profile_stud.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
