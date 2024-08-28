<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require "conn.php";


if ($_SESSION['usertype'] != 'TEACHER') {
    session_destroy();
    header("location: login.php");
    exit();
}

$branch = mysqli_escape_string($conn, $_GET['branch']);
$batch = mysqli_escape_string($conn, $_GET['batch']);
$semester = mysqli_escape_string($conn, $_GET['semester']);
$slot = mysqli_escape_string($conn, $_GET['slot']);

$sql = "SELECT * FROM attendance_noti WHERE is_read = 0  AND `branch`='$branch' AND `batch`='$batch' AND `semester`='$semester' AND `slot`='$slot' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$notifications = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nsql = "SELECT `name` FROM `students` WHERE `enrollment_no`='".$row['enrollment_no']."'";
        $nres = mysqli_query($conn,$nsql);
        $nrow = mysqli_fetch_assoc($nres);
        $row['name'] = $nrow['name'];
        $notifications[] = $row;
    }

    // Delete fetched notifications
    $ids = array_map(function ($notification) {
        return $notification['id'];
    }, $notifications);
    $ids_str = implode(',', $ids);

    $delete_sql = "DELETE FROM attendance_noti WHERE id IN ($ids_str)";
    if ($conn->query($delete_sql) === TRUE) {
        echo json_encode($notifications);
    } else {
        echo "Error deleting records: " . $conn->error;
    }
} else {
    echo json_encode($notifications);
}

$conn->close();
