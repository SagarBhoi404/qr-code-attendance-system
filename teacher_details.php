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
            <li class="breadcrumb-item">Teachers</li>
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
        <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Add New Teacher</button>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Teachers Details</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Teacher ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Education</th>
                                <th scope="col">Designation</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM teachers";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $row['id']; ?></th>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['education']; ?></td>
                                    <td><?php echo $row['designation']; ?></td>
                                    <td><?php echo $row['branch']; ?></td>
                                    <td>
                                        <button type="button" onclick="updatestud('<?php echo $row['id']; ?>')" class="btn btn-square btn-outline-danger btn-sm"><i class="fas fa-edit"></i></button>
                                        <button type="button" onclick="deletestud('<?php echo $row['id']; ?>')" class="btn btn-square btn-outline-success btn-sm"><i class="fas fa-trash"></i></button>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Teacher</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_teacher.php?type=add" method="post">
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Teacher ID</label>
                                <div class="col-sm-9">
                                    <input type="text" name="id" value="Teacher ID Automatic Allocate." readonly class="form-control" id="inputEmail3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Teacher Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="teachername" class="form-control" id="inputPassword3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Education</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="education" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <option value="B.E/B.Tech">B.E/B.Tech</option>
                                        <option value="M.E/M.Tech">M.E/M.Tech</option>
                                        <option value="Ph.d">Ph.d</option>
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
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Designation</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="designation" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <option value="Instructor">Instructor</option>
                                        <option value="Assistant Professor">Assistant Professor</option>
                                        <option value="Associate Professor">Associate Professor</option>
                                        <option value="Professor">Professor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="text" name="password" class="form-control" id="inputPassword3">
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
            window.location = `api_teacher.php?type=delete&enroll=${enroll}`;
        }
    }

    function updatestud(enroll) {
        window.location = `api_teacher_update.php?enroll=${enroll}`;
    }
</script>

<?php
require('footer.php');
?>