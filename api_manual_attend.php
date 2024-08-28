<?php
require "conn.php";
session_start();

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}


if (isset($_POST['batch']) && isset($_POST['semester']) && isset($_POST['branch'])) {
    $batch = mysqli_escape_string($conn, $_POST['batch']);
    $semester = mysqli_escape_string($conn, $_POST['semester']);
    $branch = mysqli_escape_string($conn, $_POST['branch']);
    $ip_address = mysqli_escape_string($conn, $_POST['clientIp']);
    $subject_code = mysqli_escape_string($conn, $_POST['subject_code']);
    $slot = mysqli_escape_string($conn, $_POST['slot']);
    $date = mysqli_escape_string($conn, $_POST['date']);
    $time = date('h:i:s a', strtotime(mysqli_escape_string($conn, $_POST['time'])));
    $students = $_POST['students'];
    $currentDay = date('l', strtotime($date));

    $success = array();
    $failed = array();



    foreach ($students as $key => $value) {

        $sql = "SELECT * FROM `students` WHERE `batch`='$batch' AND `enrollment_no`=$value AND `semester`='$semester'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows == 1) {


            $csql = "SELECT * FROM `attendance` WHERE `date`='$date' AND `enrollment_no`=$value AND `subject_code`=$subject_code AND `slot`=$slot AND `batch`='$batch'";
            $cres = mysqli_query($conn, $csql);
            if ($cres->num_rows == 0) {
                $fsql = "INSERT INTO `attendance`(`enrollment_no`, `date`, `day`, `subject_code`, `slot`, `batch`, `branch`,`semester`,`time`,`ip_address`) VALUES ('$value','$date','$currentDay', '$subject_code','$slot','$batch','$branch','$semester','$time','$ip_address')";
                $fres = mysqli_query($conn, $fsql);
                if ($fres) {
                    $success[$value] = "Attendance Marked!.";
                } else {
                    $failed[$value] = " Something went wrong!.";
                }
            } else {
                $failed[$value] = " Attendance Already Marked!.";
            }
        } else {

            $failed[$value] = "This Batch & Semester Not Allocate to you!.";
        }
    }

    $msg = '
<h5>Success Attendance</h5>
<ol>';

    foreach ($success as $key => $value) {
        $msg .= '<li>Enroll : ' . $key . '</li>';
    }

    $msg .= '</ol>
<h5>Failed Attendance</h5>
<ol>';

    foreach ($failed as $key => $value) {
        $msg .= '<li>Enroll : ' . $key . ', Reason: ' . $value . '</li>';
    }

    $msg .= '</ol>';

    $_SESSION['msg'] = '<div class="alert alert-info text-start mb-2" role="alert">
       ' . $msg . '
        </div>';
    header("location: manual_attend.php");
    exit();
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
       Please ensure to provide the correct details.
        </div>';
    header("location: manual_attend.php");
    exit();
}
