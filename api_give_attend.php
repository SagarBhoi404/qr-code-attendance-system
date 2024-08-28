<?php

require "conn.php";
session_start();


if ($_SESSION['usertype'] != 'STUDENT') {
    session_destroy();
    header("location: login.php");
    exit();
}

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
    // Convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}


$settingsql = "SELECT * FROM `settings` WHERE `id`='1'";
$settingresult = mysqli_query($conn, $settingsql);
$settingrow = mysqli_fetch_assoc($settingresult);


// Example coordinates of the classroom or campus
$validLatitude = $settingrow['lat'];
$validLongitude = $settingrow['lon'];
$allowedDistance = $settingrow['covarage']; // Allowable distance in kilometers

if (isset($_GET['data']) && isset($_GET['lat']) && isset($_GET['lon'])) {
    $data = mysqli_escape_string($conn, $_GET['data']);
    $lat = mysqli_escape_string($conn, $_GET['lat']);
    $lon = mysqli_escape_string($conn, $_GET['lon']);
    $ip_address = mysqli_escape_string($conn, $_GET['ip_address']);
    $decryptQR = base64_decode($data);
    $arrQRData = json_decode($decryptQR);
    $enrollment_no = mysqli_escape_string($conn, $_SESSION['enrollment_no']);

    // print_r($arrQRData);
    $subject_code = $arrQRData->subject_code;
    $day = $arrQRData->day;
    $slot = $arrQRData->slot;
    $batch = $arrQRData->batch;
    $semester = $arrQRData->semester;
    $branch = $arrQRData->branch;

    $qrTimestamp  = $arrQRData->qrgentime;

    $currentDate = date('Y-m-d');
    $currentDay = date('l');
    $currentTime = $date = date('h:i:s a', time());



    // Check if the QR code is within 1 hour of generation
    $currentTimestamp = time();
    if (($currentTimestamp - $qrTimestamp) > 3600) {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        QR code has expired!.
        </div>';
        header("location: give_attend.php");
        exit();
    }

    // Check geolocation
    $distance = haversineGreatCircleDistance($validLatitude, $validLongitude, $lat, $lon);
    if ($distance > $allowedDistance) {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        You are not within the allowable distance to mark attendance.
        </div>';
        header("location: give_attend.php");
        exit();
    }

    // The URL of the API
    $apiUrl = 'http://ip-api.com/json/' . $ip_address . '?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,proxy,query';

    // Fetch the contents from the URL
    $response = file_get_contents($apiUrl);

    // Check if the response is not false
    if ($response !== false) {
        // Decode the JSON response
        $ipdata = json_decode($response, true);

        // Check if decoding was successful
        if (json_last_error() === JSON_ERROR_NONE) {
            // Print the data
            // echo '<pre>';
            // print_r($ipdata);

            if ($ipdata['proxy'] != true) {
                if ($ipdata['countryCode'] === "IN") {

                    $sql = "SELECT * FROM `students` WHERE `batch`='$batch' AND `enrollment_no`=$enrollment_no AND `semester`='$semester' AND `branch`='$branch'";

                    $result = mysqli_query($conn, $sql);


                    if ($result->num_rows == 1) {

                        $isql = "SELECT * FROM `attendance` WHERE `date`='$currentDate' AND `subject_code`=$subject_code AND `slot`=$slot AND `batch`='$batch' AND `ip_address`='$ip_address'";
                        $ires = mysqli_query($conn, $isql);

                        if ($ires->num_rows == 0) {

                            $csql = "SELECT * FROM `attendance` WHERE `date`='$currentDate' AND `enrollment_no`=$enrollment_no AND `subject_code`=$subject_code AND `slot`=$slot AND `batch`='$batch'";
                            $cres = mysqli_query($conn, $csql);
                            if ($cres->num_rows == 0) {
                                $fsql = "INSERT INTO `attendance`(`enrollment_no`, `date`, `day`, `subject_code`, `slot`, `batch`,`branch`, `semester`,`time`,`ip_address`) VALUES ('$enrollment_no','$currentDate','$currentDay', '$subject_code','$slot','$batch','$branch','$semester','$currentTime','$ip_address')";
                                $fres = mysqli_query($conn, $fsql);
                                if ($fres) {
                                    $notiSQl = "INSERT INTO `attendance_noti`(`enrollment_no`, `date`, `day`, `subject_code`, `slot`, `batch`,`branch`, `semester`,`time`,`ip_address`) VALUES ('$enrollment_no','$currentDate','$currentDay', '$subject_code','$slot','$batch','$branch','$semester','$currentTime','$ip_address')";
                                    $notiREs = mysqli_query($conn, $notiSQl);

                                    $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Attendance Marked!.
                </div>';
                                    header("location: give_attend.php");
                                    exit();
                                } else {
                                    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!.
                </div>';
                                    header("location: give_attend.php");
                                    exit();
                                }
                            } else {
                                $_SESSION['msg'] = '<div class="alert alert-warning mb-2" role="alert">
        Attendance Already Marked!.
        </div>';
                                header("location: give_attend.php");
                                exit();
                            }
                        } else {
                            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        One IP Address can only give one attendance.
        </div>';
                            header("location: give_attend.php");
                            exit();
                        }
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        This Branch & Batch & Semester Not Allocate to you!.
        </div>';
                        header("location: give_attend.php");
                        exit();
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        Only Indian IP Allowed.
        </div>';
                    header("location: give_attend.php");
                    exit();
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        VPN and Proxy Usage Not Allowed. Please disable VPN and proxy.
        </div>';
                header("location: give_attend.php");
                exit();
            }
            echo '</pre>';
        }
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        VPN & Proxy Check Failed.
        </div>';
        header("location: give_attend.php");
        exit();
    }
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        QR & Location Not Provide. Refresh page and try again.
        </div>';
    header("location: give_attend.php");
    exit();
}
