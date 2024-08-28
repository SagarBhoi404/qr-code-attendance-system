<?php
// print_r($_POST);
require "conn.php";
session_start();

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

echo "<pre>";
// print_r($_POST);

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {


    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $academic_year = mysqli_escape_string($conn, $_POST['academicyear']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $batch = mysqli_escape_string($conn, $_POST['batch']);
        $branch = mysqli_escape_string($conn, $_POST['branch']);

        $esql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic_year' AND `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
        $eresult = mysqli_query($conn, $esql);

        if ($eresult->num_rows == 0) {
            $slots = $_POST['slots'];

            $Monday = $_POST['Monday'];
            $Tuesday = $_POST['Tuesday'];
            $Wednesday = $_POST['Wednesday'];
            $Thursday = $_POST['Thursday'];
            $Friday = $_POST['Friday'];
            $Saturday = $_POST['Saturday'];

            foreach ($slots as $key => $value) {

                $slot = $key + 1;

                $sql1 = "INSERT INTO `timetable`(`semester`, `batch`, `branch`,`day`, `slot`,`slotlabel` ,`subject_code`, `academic_year`) VALUES ('$semester','$batch','$branch','Monday','$slot','$value' ,'$Monday[$key]','$academic_year')";
                $result1 = mysqli_query($conn, $sql1);

                $sql2 = "INSERT INTO `timetable`(`semester`, `batch`, `branch`,`day`, `slot`,`slotlabel` , `subject_code`, `academic_year`) VALUES ('$semester','$batch','$branch','Tuesday','$slot','$value' ,'$Tuesday[$key]','$academic_year')";
                $result2 = mysqli_query($conn, $sql2);

                $sql3 = "INSERT INTO `timetable`(`semester`, `batch`, `branch`,`day`, `slot`,`slotlabel` , `subject_code`, `academic_year`) VALUES ('$semester','$batch','$branch','Wednesday','$slot','$value' ,'$Wednesday[$key]','$academic_year')";
                $result3 = mysqli_query($conn, $sql3);

                $sql4 = "INSERT INTO `timetable`(`semester`, `batch`, `branch`,`day`, `slot`,`slotlabel` , `subject_code`, `academic_year`) VALUES ('$semester','$batch','$branch','Thursday','$slot','$value' ,'$Thursday[$key]','$academic_year')";
                $result4 = mysqli_query($conn, $sql4);

                $sql5 = "INSERT INTO `timetable`(`semester`, `batch`, `branch`,`day`, `slot`, `slotlabel` ,`subject_code`, `academic_year`) VALUES ('$semester','$batch','$branch','Friday','$slot','$value' ,'$Friday[$key]','$academic_year')";
                $result5 = mysqli_query($conn, $sql5);

                $sql6 = "INSERT INTO `timetable`(`semester`, `batch`, `branch`,`day`, `slot`,`slotlabel` , `subject_code`, `academic_year`) VALUES ('$semester','$batch','$branch','Saturday','$slot','$value' ,'$Saturday[$key]','$academic_year')";
                $result6 = mysqli_query($conn, $sql6);
            }


            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
        Time Table Added.
        </div>';
            header("location: timetable.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-warning mb-2" role="alert">
             Time Table Already Exits.
             </div>';
            header("location: timetable.php");
            exit();
            // die("Time Table Already Exits.");
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $academicyear = $_POST['academicyear'];
        $semester = $_POST['semester'];
        $batch = $_POST['batch'];
        $branch = $_POST['branch'];

        $esql = "SELECT * FROM `timetable` WHERE `academic_year`='$academicyear' AND `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
        $eresult = mysqli_query($conn, $esql);
        print_r($eresult);

        if ($eresult->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($eresult)) {
                $sql = "DELETE FROM `timetable` WHERE id=" . $row['id'];
                $result = mysqli_query($conn, $sql);
            }


            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
            Time Table Deleted.
            </div>';
            header("location: timetable.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-warning mb-2" role="alert">
             Time Table Not Found!.
             </div>';
            header("location: timetable.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
