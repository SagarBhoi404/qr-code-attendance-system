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
            <li class="breadcrumb-item">Subjects</li>
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
        <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Add New Subject</button>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Subjects Details</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Subject Description</th>
                                <th scope="col">Subject Abbrevation</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Teacher ID</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM subjects";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $row['subject_code']; ?></th>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['abbreviation']; ?></td>
                                    <td><?php echo $row['semester']; ?></td>
                                    <td><?php echo $row['branch']; ?></td>
                                    <td><?php echo $row['teacher_id']; ?></td>
                                    <td>
                                        <button type="button" onclick="updatestud('<?php echo $row['subject_code']; ?>')" class="btn btn-square btn-outline-danger btn-sm"><i class="fas fa-edit"></i></button>
                                        <button type="button" onclick="deletestud('<?php echo $row['subject_code']; ?>')" class="btn btn-square btn-outline-success btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Subject</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_subject.php?type=add" method="post">
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Subject Code</label>
                                <div class="col-sm-9">
                                    <input type="number" name="subjectcode" class="form-control" id="inputEmail3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Subject Description</label>
                                <div class="col-sm-9">
                                    <input type="text" name="description" class="form-control" id="inputPassword3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Subject Abbrevation</label>
                                <div class="col-sm-9">
                                    <input type="text" name="abbrevation" class="form-control" id="inputPassword3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semeter</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="semester" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
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
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Branch</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="branch" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <option value="Computer Engineering">Computer Engineering</option>
                                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                                        <option value="Electrical Engineering">Electrical Engineering</option>
                                        <option value="Civil Engineering">Civil Engineering</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Taught By</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="teacherid" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <?php
                                        $tsql = "SELECT * FROM teachers";
                                        $tresult = mysqli_query($conn, $tsql);
                                        while ($trow = mysqli_fetch_assoc($tresult)) {
                                        ?>
                                            <option value="<?php echo $trow['id']; ?>"><?php echo $trow['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
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

<script>
    function deletestud(enroll) {
        let isDelete = confirm('Are you sure to delete?');
        if (isDelete) {
            window.location = `api_subject.php?type=delete&enroll=${enroll}`;
        }
    }

    function updatestud(enroll) {
        window.location = `api_subject_update.php?enroll=${enroll}`;
    }
</script>

<?php
require('footer.php');
?>