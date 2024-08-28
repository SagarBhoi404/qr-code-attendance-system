<?php
require('header.php');
require('conn.php');


if ($_SESSION['usertype'] != 'TEACHER') {
    session_destroy();
    header("location: login.php");
    exit();
}




if (isset($_GET['subject_code']) && isset($_GET['slot']) && isset($_GET['batch']) && isset($_GET['day'])) {
    $subject_code = mysqli_escape_string($conn, $_GET['subject_code']);
    $slot = mysqli_escape_string($conn, $_GET['slot']);
    $batch = mysqli_escape_string($conn, $_GET['batch']);
    $day = mysqli_escape_string($conn, $_GET['day']);
    $semester = mysqli_escape_string($conn, $_GET['semester']);
    $branch = mysqli_escape_string($conn, $_GET['branch']);
    $slotlabel = mysqli_escape_string($conn, $_GET['slotlabel']);

    $currentDate = date('d-m-Y');
    $currentDay = date('l');
    $currentTime = date('h:i:s a', time());

    $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $subject_code;
    $fsresult = mysqli_query($conn, $fssql);
    $fsrow = mysqli_fetch_assoc($fsresult);

    $sql = "SELECT * FROM `timetable` WHERE `subject_code`='$subject_code' AND `day`='$day' AND `slot`='$slot' AND `batch`='$batch'";
    $result = mysqli_query($conn, $sql);

    $currentTimestamp = time();
    if ($result->num_rows == 1) {


        $qrdata = array("subject_code" => $subject_code, "day" => $day, "slot" => $slot, "batch" => $batch, "currentDate" => $currentDate, "currentDay" => $currentDay, "qrgentime" => $currentTimestamp, "semester" => $semester, "branch" => $fsrow['branch']);
        $encryptQR = base64_encode(json_encode($qrdata));
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
        Class Not Availbale!.
    </div>';
        header("location: take_attendance.php");
        exit();
    }
}
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item">Take Attendance</li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $fsrow['name']; ?></li>
        </ol>
    </nav>
</div>
<style>
    #notifications {
        list-style-type: none;
        padding: 0;
    }

    .notification {
        padding: 10px;
        background-color: #f9f9f9;
        margin-bottom: 5px;
        border: 1px solid #ddd;
        opacity: 0;
        animation: fadeInUp 1s forwards;
        color: green;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <div class="text-center w-100">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Take Attendance</h6>

                <div>
                    <h6 class="text-success">Real Time Attendance Logs</h6>
                    <ul id="notifications"></ul>
                </div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Subject</td>
                            <td><?php echo $fsrow['name']; ?></td>
                        </tr>
                        <tr>
                            <td>Batch</td>
                            <td><?php echo $batch; ?></td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td><?php echo $semester; ?></td>
                        </tr>
                        <tr>
                            <td>Today Date</td>
                            <td><?php echo $currentDate; ?></td>
                        </tr>
                        <tr>
                            <td>Today Day</td>
                            <td><?php echo $currentDay; ?></td>
                        </tr>
                        <tr>
                            <td>Slot</td>
                            <td><?php echo $slotlabel; ?></td>
                        </tr>
                        <tr>
                            <td>QR Code <br>
                                <p>(Students are Scan QR Code and Mark Attendance)</p>
                            </td>
                            <td> <img class="img-fluid" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?php echo $encryptQR; ?>" title="qr generate" /></td>
                        </tr>

                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>
<!-- Blank End -->


<script>
    function fetchNotifications() {
        $.ajax({
            url: 'api_noti.php',
            type: 'GET',
            dataType: 'json',
            data: {
                branch: "<?php echo $branch; ?>",
                batch: "<?php echo $batch; ?>",
                semester: "<?php echo $semester; ?>",
                slot: "<?php echo $slot; ?>"
            },
            success: function(data) {
                $('#notifications').empty();

                data.forEach(function(notification) {
                    var notificationElement = $(`<li class="notification"> ${notification.time} : <strong>${notification.name}</strong> [${notification.ip_address}] </li>`);
                    $('#notifications').prepend(notificationElement);

                    // Remove the notification after 10 seconds
                    setTimeout(function() {
                        notificationElement.fadeOut(1000, function() {
                            $(this).remove();
                        });
                    }, 4000); // 10 seconds

                });
            }
        });
    }

    setInterval(fetchNotifications, 2000); // Poll every 5 seconds
</script>

<?php
require('footer.php');
?>