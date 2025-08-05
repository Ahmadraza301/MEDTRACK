<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
}
$page_title = 'Admin Home - NearBy ShopWay';
include('../components/connect.php');
include('../components/admin_header.php');

//firstly this php code will execute - if cases will not be executed bcz not satisfieses condition - 
// fetch id which called update from update link of admin.php function(not id which is currently logged in)
if (isset($_GET['update'])) {
    $id = $_GET['update'];
}
//when we visit update_ac page directly without calling from accounts section then id var from get request will be null. so set it to current logged iin session admin value
else{
    $id = $admin_id;
}

if (isset($_POST['submit_profile'])) {
    
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $update_profile = "UPDATE admin_ac SET name = '$name' WHERE id = '$id'";
    $update = mysqli_query($conn, $update_profile);
}
if (isset($_POST['submit_password'])) {
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    if ($pass != $cpass) {
        $message[] = "Confirm Password not matched!";
    } else {
        $pass = sha1($pass);
        $update_profile = "UPDATE admin_ac SET password = '$pass' WHERE id = '$id'";
        $update = mysqli_query($conn, $update_profile);
    }
}

?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="admins.php">Admins</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <?php
    // access id from above id var defined on php code.
    $admin_query = "SELECT * FROM admin_ac WHERE id = '$id'";
    $admin_query_execute = mysqli_query($conn, $admin_query);
    $fetch = mysqli_fetch_assoc($admin_query_execute);
    ?>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="../assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <h2><?php echo $fetch['name']; ?></h2>
                        <h6>Senior Admin</h6>
                        <div class="social-links mt-2">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade profile-edit pt-3 show active" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form action="" method="post">
                                    <div class="row mb-3 ">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="../assets/img/profile-img.jpg" alt="Profile">
                                            <div class="pt-2">
                                                <!-- <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                                <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Admin Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text" class="form-control" id="adminName" value="<?= $fetch['name'] ?>">
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="submit_profile">Save Changes</button>
                                    </div>
                                </form>

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form action="" method="post">
                                    <?php
                                    if (isset($message)) {
                                        foreach ($message as $message) {
                                            echo '
                            <div class="alert alert-danger text-center">
                            <?php                         
                            <span>' . $message . '</span>
                            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                            </div>
                            ';
                                        }
                                    }
                                    ?>
                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="pass" type="password" class="form-control" id="newPassword" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="cpass" type="password" class="form-control" id="renewPassword" required>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="submit_password">Change Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
include('../components/admin_footer.php');
?>