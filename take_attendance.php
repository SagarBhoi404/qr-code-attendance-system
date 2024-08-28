<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'TEACHER') {
    session_destroy();
    header("location: login.php");
    exit();
}


$teacher_id = $_SESSION['teacher_id'];
$teacher_sql = "SELECT * FROM teachers WHERE `id`=$teacher_id";
$teacher_res = mysqli_query($conn, $teacher_sql);
$teacher_row = mysqli_fetch_assoc($teacher_res);

// print_r($teacher_row['branch']);
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item active" aria-current="page">Take Attendance</li>
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
                <h6 class="mb-4">Take Attendance</h6>
                <form class="row gy-2 gx-3 align-items-center border p-2" action="take_attendance.php" method="get">
                    <div class="col-auto">
                        <p>View Time Table</p>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="semester" id="autoSizingSelect">
                            <option value="">Select Semester</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                            <option value="3">Third</option>
                            <option value="4">Fourth</option>
                            <option value="5">Fifth</option>
                            <option value="6">Sixth</option>
                            <option value="7">Seven</option>
                            <option value="8">Eight</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="batch" id="autoSizingSelect">
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
                        <select class="form-select" name="academic" id="autoSizingSelect">
                            <option value="">Select Academic Year</option>
                            <option value="2022-23">2022-23</option>
                            <option value="2023-24">2023-24</option>
                            <option value="2024-25">2024-25</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search Time Table</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['academic'])) {
                ?>

                    <h6 class="mb-3 text-center mt-3 text-danger">Time Table Details : Academic Year-[<?php echo $_GET['academic']; ?>], Batch-[<?php echo $_GET['batch']; ?>], Semester-[<?php echo $_GET['semester']; ?>]</h6>
                    <!-- <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Slot 1</th>
                                    <th scope="col">Slot 2</th>
                                    <th scope="col">Slot 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $semester = $_GET['semester'];
                                $batch = $_GET['batch'];
                                $academic = $_GET['academic'];
                                $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND `semester`='$semester' AND `batch`='$batch' AND `branch`='" . $teacher_row['branch'] . "'";
                                // echo "<pre>";
                                $result = mysqli_query($conn, $sql);
                                $daychanger = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // print_r($result);
                                    // echo $daychanger;
                                    if ($daychanger == 1) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['day']; ?></td>

                                        <?php
                                    }

                                    $fssql = "SELECT * FROM `subjects` WHERE `subject_code`=" . $row['subject_code'];
                                    $fsresult = mysqli_query($conn, $fssql);
                                    $fsrow = mysqli_fetch_assoc($fsresult);
                                    echo ' <td>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>' . $fsrow['name'];

                                    if ($fsrow['teacher_id'] == $_SESSION['teacher_id'] && $row['day'] == date('l')) {
                                        echo '<a href="take_attend.php?subject_code=' . $row['subject_code'] . '&slot=' . $row['slot'] . '&batch=' . $row['batch'] . '&day=' . $row['day'] . '&semester=' . $row['semester'] . '" title="Take Attendance"><i class="fas fa-clipboard-check ms-2"></i></a>';
                                    }

                                    echo '</p>
                                        </div>
                                    </div>
                                </td>';


                                    if ($daychanger == 3) {
                                        ?>
                                        </tr>
                                <?php
                                    }
                                    if ($daychanger == 3) {
                                        $daychanger = 1;
                                    } else {
                                        $daychanger += 1;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> -->

                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $batch = mysqli_escape_string($conn, $_GET['batch']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='" . $teacher_row['branch'] . "' AND `semester`='$semester' AND `batch`='$batch'";
                    $sqlslot = "SELECT DISTINCT `slot`,`slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='" . $teacher_row['branch'] . "' AND `semester`='$semester' AND `batch`='$batch'";
                    $result1 = mysqli_query($conn, $sqlslot);
                    $result2 = mysqli_query($conn, $sql);

                    $slots = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $slots[$row['slot']] = $row['slotlabel'];
                    }

                    $timetable = [];
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $day = $row['day'];
                        $slot = $row['slot'];
                        if (!isset($timetable[$day])) {
                            $timetable[$day] = [];
                        }
                        $timetable[$day][$slot] = $row['subject_code'];
                    }

                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Day</th>
                                    <?php foreach ($slots as $slot => $slotLabel) : ?>
                                        <th scope="col"><?php echo $slotLabel; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                foreach ($daysOfWeek as $day) :
                                    $dayData = isset($timetable[$day]) ? $timetable[$day] : [];
                                ?>
                                    <tr>
                                        <td><?php echo $day; ?></td>
                                        <?php foreach ($slots as $slot => $slotLabel) : ?>
                                            <td>
                                                <?php
                                                $subjectCode = isset($dayData[$slot]) ? $dayData[$slot] : '';
                                                if ($subjectCode) {
                                                    $subjectSQL = "SELECT `name` FROM `subjects` WHERE `subject_code`='$subjectCode'";
                                                    $subjectRes = mysqli_query($conn, $subjectSQL);
                                                    $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                    echo $subjectRow['name'];

                                                    if ($fsrow['teacher_id'] == $_SESSION['teacher_id'] && $day == date('l')) {
                                                        echo '<a href="take_attend.php?subject_code=' . $subjectCode . '&slot=' . $slot . '&batch=' . $batch . '&day=' . $day . '&semester=' . $semester . '&branch='. $teacher_row['branch'].'&slotlabel='. $slotLabel.'" title="Take Attendance"><i class="fas fa-clipboard-check ms-2"></i></a>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



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