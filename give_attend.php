<?php
require('header.php');
require('conn.php');


if ($_SESSION['usertype'] != 'STUDENT') {
    session_destroy();
    header("location: login.php");
    exit();
}

?>
<style>
    .vid {
        border: solid 1px blueviolet;
    }

    @media only screen and (max-width: 600px) {
        .vid {
            width: 300px;
            height: 300px;
        }
    }

    @media only screen and (max-width: 900px) {
        .vid {
            width: 400px;
            height: 300px;
        }
    }
</style>

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item">Mark Attendance</li>
        </ol>
    </nav>
</div>


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
                <h6 class="mb-1">Mark Attendance</h6>
                <p class="mb-4 text-primary">*Note: Scan QR Code to Mark Attendance.</p>
                <div class="alert alert-danger fw-bold" id="locationWarnAlert" role="alert">
                    Please Allow Location Permission To Give Attendance.
                </div>
                <div class="text-center">
                    <video id="preview" style="width: auto;" class="img-thumbnail"></video>
                </div>
                <div class="btn-group btn-group-toggle mb-5 text-center w-100" data-toggle="buttons">
                    <label class="btn btn-primary active">
                        <input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" name="options" value="2" autocomplete="off"> Back Camera
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->

<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<!-- <script type="text/javascript">
    
</script> -->

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    getLocation()

    function showPosition(position) {
        let locationWarnAlert = document.getElementById("locationWarnAlert");
        locationWarnAlert.style.display = "none";

        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        let clientIp = '';

        // Fetch the client's IP address
        $.get('https://api.ipify.org?format=json', function(data) {
            clientIp = data.ip;
        });


        var scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: false
        });
        scanner.addListener('scan', function(content) {
            // alert(content);
            window.location.href = `api_give_attend.php?data=${content}&lat=${lat}&lon=${lon}&ip_address=${clientIp}`;
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
                $('[name="options"]').on('change', function() {
                    if ($(this).val() == 1) {
                        if (cameras[0] != "") {
                            scanner.start(cameras[0]);
                        } else {
                            alert('No Front camera found!');
                        }
                    } else if ($(this).val() == 2) {
                        if (cameras[1] != "") {
                            scanner.start(cameras[1]);
                        } else {
                            alert('No Back camera found!');
                        }
                    }
                });
            } else {
                console.error('No cameras found.');
                alert('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
            alert(e);
        });
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById("locationWarnAlert").innerHTML = "Please Allow Location Permission To Give Attendance.";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById("locationWarnAlert").innerHTML = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                document.getElementById("locationWarnAlert").innerHTML = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById("locationWarnAlert").innerHTML = "An unknown error occurred.";
                break;
        }
    }
</script>
<?php
require('footer.php');
?>