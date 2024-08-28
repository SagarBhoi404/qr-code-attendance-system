<?php
session_start();
require "conn.php";

if (isset($_GET['type']) && $_GET['type'] == "admin") {
    $username = mysqli_escape_string($conn, $_POST['username']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if ($result->num_rows == 1) {
        if (password_verify($password, $row['password'])) {
            if ($row['status'] == 1) {
                if ($row['user_type'] == "ADMIN") {
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['usertype'] = $row['user_type'];
                    $_SESSION['logged'] = true;
                    header("location: ./");
                    exit();
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
                 Invalid login credentials!.
            </div>';
                    header("location: login.php");
                    exit();
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
            Invalid login credentials!.
        </div>';
                header("location: login.php");
                exit();
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
            Invalid login credentials!.
        </div>';
            header("location: login.php");
            exit();
        }
    }
} else if (isset($_GET['type']) && $_GET['type'] == "teacher") {
    $teacherid = mysqli_escape_string($conn, $_POST['teacherid']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM teachers WHERE id='$teacherid' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);


    if ($result->num_rows == 1) {
        $_SESSION['username'] = $row['name'];
        $_SESSION['teacher_id'] = $row['id'];
        $_SESSION['usertype'] = "TEACHER";
        $_SESSION['logged'] = true;
        header("location: ./");
        exit();;
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
                Invalid login credentials!.
            </div>';
        header("location: login.php");
        exit();
    }
} else if (isset($_GET['type']) && $_GET['type'] == "student") {
    $enroll = mysqli_escape_string($conn, $_POST['enroll']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM students WHERE enrollment_no=$enroll AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);


    if ($result->num_rows == 1) {
        $_SESSION['username'] = $row['name'];
        $_SESSION['enrollment_no'] = $row['enrollment_no'];
        $_SESSION['usertype'] = "STUDENT";
        $_SESSION['logged'] = true;
        header("location: ./");
        exit();
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
            Invalid login credentials!.
        </div>';
        header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>QR Code Attendance System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign In Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="index.html" class="">
                                <h3 class="text-primary"><i class="fas fa-qrcode"></i> QRCAS</h3>
                            </a>
                            <h3>Sign In</h3>
                        </div>
                        <div class="my-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" onchange="myFunction()" type="radio" checked name="inlineRadioOptions" id="inlineRadio1" value="admin">
                                <label class="form-check-label" for="inlineRadio1">Admin</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" onchange="myFunction()" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="teacher">
                                <label class="form-check-label" for="inlineRadio2">Teacher</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" onchange="myFunction()" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="student">
                                <label class="form-check-label" for="inlineRadio3">Student</label>
                            </div>
                        </div>

                        <!-- Admin login Form -->
                        <form id="form_admin" action="login.php?type=admin" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="username" placeholder="name123">
                                <label for="floatingInput">Username</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                        </form>
                        <!-- End Admin login Form -->

                        <!-- Teacher login Form -->
                        <form id="form_teacher" action="login.php?type=teacher" method="post" hidden="true">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="teacherid" placeholder="name123">
                                <label for="floatingInput">Teacher ID</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                        </form>
                        <!-- End Teacher login Form -->

                        <!-- Student login Form -->
                        <form id="form_student" action="login.php?type=student" method="post" hidden="true">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="enroll" placeholder="name123">
                                <label for="floatingInput">Enrollment No</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                        </form>
                        <!-- End Student login Form -->

                        <?php
                        if (isset($_SESSION['msg'])) {
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>


    <script>
        function myFunction() {
            if (document.getElementById("inlineRadio1").checked) {
                $('#form_admin').removeAttr('hidden');
                $('#form_teacher').attr("hidden", true);
                $('#form_student').attr("hidden", true);
            } else if (document.getElementById("inlineRadio2").checked) {
                $('#form_admin').attr("hidden", true);
                $('#form_teacher').removeAttr('hidden');
                $('#form_student').attr("hidden", true);
            } else if (document.getElementById("inlineRadio3").checked) {
                $('#form_admin').attr("hidden", true);
                $('#form_teacher').attr("hidden", true);
                $('#form_student').removeAttr('hidden');
            }
        }
    </script>
</body>

</html>