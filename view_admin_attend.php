<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

$sql = "SELECT * FROM `attendance` ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item">View Attendance</li>
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
                <h6 class="mb-4">View Attendance</h6>
                <form class="row gy-2 gx-3 align-items-center border p-2 mb-4" action="view_admin_attend.php" method="get">
                    <div class="col-auto d-flex">
                        <label class="" for="autoSizingSelect">Start Date</label>
                        <input type="date" name="startdate" date_format="yyyy-mm-dd" class="form-control" required>
                    </div>
                    <div class="col-auto d-flex">
                        <label class="" for="autoSizingSelect">End Date</label>
                        <input type="date" name="enddate" class="form-control" required>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="subject" id="autoSizingSelect" required>
                            <option value="">Select Subject</option>
                            <?php
                            $sssql = "SELECT * FROM `subjects`";
                            $ssresult = mysqli_query($conn, $sssql);
                            while ($erow = mysqli_fetch_array($ssresult)) {
                                echo '<option value="' . $erow['subject_code'] . '">' . $erow['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="batch" id="autoSizingSelect" required>
                            <option value="">Select Batch</option>
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="A3">A3</option>
                            <option value="A4">A4</option>
                            <option value="A5">A5</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="branch" id="autoSizingSelect" required>
                            <option value="">Select Branch</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['subject']) && isset($_GET['batch']) && isset($_GET['branch'])) {
                    $sdateString = $_GET['startdate']; // Replace with your date string
                    $edateString = $_GET['enddate']; // Replace with your date string
                    $subject_code = $_GET['subject'];
                    $batch = $_GET['batch'];
                    $branch = $_GET['branch'];

                    $sql = "SELECT * FROM attendance WHERE STR_TO_DATE(`date`, '%Y-%m-%d') BETWEEN STR_TO_DATE('$sdateString', '%Y-%m-%d') AND STR_TO_DATE('$edateString', '%Y-%m-%d') AND `subject_code`=$subject_code AND `batch`='$batch' AND `branch`='$branch' ORDER BY id DESC";
            
                    $result = mysqli_query($conn, $sql);
                ?>
                    <h6>Filtered : Start Date= <?php echo $_GET['startdate']; ?> , End Date= <?php echo $_GET['enddate']; ?></h6>
                    <table class="table table-bordered table-striped text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Enrollment No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Day</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Batch</th>
                                <th scope="col">QR Code Scanned @</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $sr; ?></th>
                                    <td><?php echo $row['enrollment_no']; ?></td>
                                    <td><?php
                                        $efssql = "SELECT * FROM `students` WHERE `enrollment_no`=" . $row['enrollment_no'];
                                        $efsresult = mysqli_query($conn, $efssql);
                                        $efsrow = mysqli_fetch_assoc($efsresult);
                                        echo $efsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['day']; ?></td>
                                    <td><?php
                                        $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $row['subject_code'];
                                        $fsresult = mysqli_query($conn, $fssql);
                                        $fsrow = mysqli_fetch_assoc($fsresult);
                                        echo $fsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['slot']; ?></td>
                                    <td><?php echo $row['batch']; ?></td>
                                    <td><?php echo $row['time']; ?></td>
                                </tr>
                            <?php
                                $sr++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                } else {
                ?>
                    <table class="table table-bordered table-striped text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">Enrollment No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Day</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Batch</th>
                                <th scope="col">QR Code Scanned @</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sr = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $sr; ?></th>
                                    <td><?php echo $row['enrollment_no']; ?></td>
                                    <td><?php
                                        $efssql = "SELECT * FROM `students` WHERE `enrollment_no`=" . $row['enrollment_no'];
                                        $efsresult = mysqli_query($conn, $efssql);
                                        $efsrow = mysqli_fetch_assoc($efsresult);
                                        echo $efsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['day']; ?></td>
                                    <td><?php
                                        $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $row['subject_code'];
                                        $fsresult = mysqli_query($conn, $fssql);
                                        $fsrow = mysqli_fetch_assoc($fsresult);
                                        echo $fsrow['name'];
                                        ?></td>
                                    <td><?php echo $row['slot']; ?></td>
                                    <td><?php echo $row['batch']; ?></td>
                                    <td><?php echo $row['time']; ?></td>
                                </tr>
                            <?php
                                $sr++;
                            }
                            ?>
                        </tbody>
                    </table>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->

<?php
require('footer.php');
?>