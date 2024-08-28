<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Time Table</li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
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
        <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Set New Time Table</button>
        <button type="button" class="btn btn-outline-danger m-2" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-plus me-2"></i>Delete Time Table</button>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Time Table</h6>
                <form class="row gy-2 gx-3 align-items-center border p-2" action="timetable.php" method="get">
                    <div class="col-auto">
                        <p>View Time Table</p>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="branch" required>
                            <option value="">Select Branch</option>
                            <option value="Computer Engineering">Computer Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Electrical Engineering">Electrical Engineering</option>
                        </select>
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
                if (isset($_GET['batch']) && isset($_GET['semester']) && isset($_GET['academic']) && isset($_GET['branch'])) {
                ?>

                    <h6 class="mb-3 text-center mt-3 text-danger">Time Table Details : Academic Year-[<?php echo $_GET['academic']; ?>],Branch-[<?php echo $_GET['branch']; ?>] , Batch-[<?php echo $_GET['batch']; ?>], Semester-[<?php echo $_GET['semester']; ?>]</h6>

                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $batch = mysqli_escape_string($conn, $_GET['batch']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    $branch = mysqli_escape_string($conn, $_GET['branch']);
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
                    $sqlslot = "SELECT DISTINCT `slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND  `branch`='$branch' AND `semester`='$semester' AND `batch`='$batch'";
                    $result1 = mysqli_query($conn, $sqlslot);
                    $result2 = mysqli_query($conn, $sql);

                    $slots = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $slots[] = $row['slotlabel'];
                    }

                    $timetable = [];
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $timetable[$row['day']][] = $row['subject_code'];
                    }

                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Day</th>
                                    <?php foreach ($slots as $slot) : ?>
                                        <th scope="col"><?php echo $slot; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                $slotCount = count($slots);

                                foreach ($daysOfWeek as $day) :
                                    $dayData = isset($timetable[$day]) ? $timetable[$day] : array_fill(0, $slotCount, '');
                                ?>
                                    <tr>
                                        <td><?php echo $day; ?></td>
                                        <?php for ($i = 0; $i < $slotCount; $i++) : ?>
                                            <td><?php
                                                $subjectCode =  isset($dayData[$i]) ? $dayData[$i] : '';
                                                $subjectSQL = "SELECT * FROM `subjects` WHERE `subject_code`=$subjectCode";
                                                $subjectRes = mysqli_query($conn, $subjectSQL);
                                                $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                echo $subjectRow['name'];
                                                ?></td>
                                        <?php endfor; ?>
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



<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Set New Time Table</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_timetable.php?type=add" method="post">
                            <div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Academic Year</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="academicyear" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="2022-23">2022-23</option>
                                            <option value="2023-24">2023-24</option>
                                            <option value="2024-25">2024-25</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Branch</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="branch" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="Computer Engineering">Computer Engineering</option>
                                            <option value="Civil Engineering">Civil Engineering</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                            <option value="Electrical Engineering">Electrical Engineering</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semeter</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="semester" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
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
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Batch</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="batch" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="A1">A1</option>
                                            <option value="A2">A2</option>
                                            <option value="A3">A3</option>
                                            <option value="A4">A4</option>
                                            <option value="A5">A5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <input type="text" class="form-control form-control-sm" placeholder="Slot Label" id="slotlabelinput">
                                <button class="btn btn-dark btn-sm" onclick="addslot()" type="button">Add Slot</button>
                            </div>
                            <p class="text-danger">*Note: Do not give same label.</p>
                            <div class="table-responsive">
                                <table class="table table-borderless text-center">
                                    <thead>
                                        <tr id="slotlabelbox">
                                            <th></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="mondaySlotsBox">
                                            <td>Monday</td>
                                        </tr>

                                        <tr id="tuesdaySlotsBox">
                                            <td>Tuesday</td>

                                        </tr>

                                        <tr id="wednesdaySlotsBox">
                                            <td>Wednesday</td>

                                        </tr>

                                        <tr id="thursdaySlotsBox">
                                            <td>Thursday</td>

                                        </tr>

                                        <tr id="fridaySlotsBox">
                                            <td>Friday</td>

                                        </tr>

                                        <tr id="saturdaySlotsBox">
                                            <td>Saturday</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Time Table</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_timetable.php?type=delete" method="post">
                            <div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Branch</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="branch" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="Computer Engineering">Computer Engineering</option>
                                            <option value="Civil Engineering">Civil Engineering</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                            <option value="Electrical Engineering">Electrical Engineering</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Academic Year</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="academicyear" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="2022-23">2022-23</option>
                                            <option value="2023-24">2023-24</option>
                                            <option value="2024-25">2024-25</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semeter</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="semester" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
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
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Batch</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="batch" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="A1">A1</option>
                                            <option value="A2">A2</option>
                                            <option value="A3">A3</option>
                                            <option value="A4">A4</option>
                                            <option value="A5">A5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Delete</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addslot() {
        let timelabel = $('#slotlabelinput').val();
        if (timelabel == "") {
            alert("Add Time Label");
        } else {
            $('#slotlabelbox').append(`<th scope="col">${timelabel} <input type="text" readonly value="${timelabel}" name="slots[]" hidden></th>`);

            $('#mondaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Monday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM subjects";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#tuesdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Tuesday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM subjects";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#wednesdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Wednesday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM subjects";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#thursdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Thursday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM subjects";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#fridaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Friday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM subjects";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#saturdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Saturday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Subject</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM subjects";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['subject_code']; ?>"><?php echo $trow['name']; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);

            $('#slotlabelinput').val('');
        }
    }
</script>

<?php
require('footer.php');
?>