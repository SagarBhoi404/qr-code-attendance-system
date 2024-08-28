<?php
require('header.php');
require('conn.php');
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Settings</li>
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
        <!-- <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Add New Subject</button> -->
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Settings</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Campus Location</th>
                                <th scope="col">Latitude </th>
                                <th scope="col">Longitude</th>
                                <th scope="col">Covarage Area</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $settingsql = "SELECT * FROM `settings` WHERE `id`='1'";
                            $settingresult = mysqli_query($conn, $settingsql);
                            $settingrow = mysqli_fetch_assoc($settingresult);
                            ?>
                            <tr>
                                <td><?php echo $settingrow['location']; ?></td>
                                <td><?php echo $settingrow['lat']; ?></td>
                                <td><?php echo $settingrow['lon']; ?></td>
                                <td><?php echo $settingrow['covarage']; ?> KM</td>
                                <td><button type="button" onclick="updateSetting()" class="btn btn-square btn-outline-danger btn-sm"><i class="fas fa-edit"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->


<script>
    function updateSetting() {
        window.location = `api_settings_update.php`;
    }
</script>

<?php
require('footer.php');
?>