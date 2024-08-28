<?php
require('header.php');
require "conn.php";


?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item active" aria-current="page">Home</li>
        </ol>
    </nav>
</div>


<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
        <div class="col-md-6 text-center">
            <h3>Welcome To QR Code Attendance System</h3>

            <?php
            if (isset($_SESSION['teacher_id'])) {
                $teacher_id = $_SESSION['teacher_id'];
                $teacher_sql = "SELECT * FROM teachers WHERE `id`=$teacher_id";
                $teacher_res = mysqli_query($conn, $teacher_sql);
                $teacher_row = mysqli_fetch_assoc($teacher_res);
            ?>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Teacher ID</th>
                            <td><?php echo $teacher_row['id']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Name</th>
                            <td><?php echo $teacher_row['name']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Education</th>
                            <td><?php echo $teacher_row['education']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Designation</th>
                            <td><?php echo $teacher_row['designation']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Branch</th>
                            <td><?php echo $teacher_row['branch']; ?></td>
                        </tr>

                    </tbody>
                </table>

            <?php
            } else  if (isset($_SESSION['enrollment_no'])) {
                $student_id = $_SESSION['enrollment_no'];
                $student_sql = "SELECT * FROM students WHERE `enrollment_no`=$student_id";
                $student_res = mysqli_query($conn, $student_sql);
                $student_row = mysqli_fetch_assoc($student_res);
            ?>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Enrollment No</th>
                            <td><?php echo $student_row['enrollment_no']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Name</th>
                            <td><?php echo $student_row['name']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Semester</th>
                            <td><?php echo $student_row['semester']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Branch</th>
                            <td><?php echo $student_row['branch']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Roll No</th>
                            <td><?php echo $student_row['roll_no']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Batch</th>
                            <td><?php echo $student_row['batch']; ?></td>
                        </tr>

                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Blank End -->

<?php
require('footer.php');
?>