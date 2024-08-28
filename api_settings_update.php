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
            <li class="breadcrumb-item">Details</li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>
</div>


<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <?php
    $sql = "SELECT * FROM settings WHERE id='1'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>


    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <form action="api_settings.php?type=update" method="post">
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Location</label>
                        <div class="col-sm-9">
                            <input type="text" name="location" class="form-control" value="<?php echo $row['location']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Latitude</label>
                        <div class="col-sm-9">
                            <input type="text" name="lat" class="form-control" value="<?php echo $row['lat']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Longitude</label>
                        <div class="col-sm-9">
                            <input type="text" name="lon" class="form-control" value="<?php echo $row['lon']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Covarage</label>
                        <div class="col-sm-9">
                            <input type="text" name="covarage" class="form-control" value="<?php echo $row['covarage']; ?>">
                            <span class="text-success fs-6 d-block">*Note: How many area are available for students to give their attendance.</span>
                            <span class="text-success fs-6 d-block">*Note: Examples: 0.5 KM, 1.0 KM , 5.0 KM</span>
                            <span class="text-success fs-6 d-block">*Note: Only Give Number In Input Not KM.</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->


<?php
require('footer.php');
?>