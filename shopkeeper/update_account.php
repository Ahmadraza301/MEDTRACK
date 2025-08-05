<?php
session_start();
if (!isset($_SESSION['shopkeeper_id'])) {
    header('location: login.php');
}
$page_title = 'Shopkeeper Account - NearBy ShopWay';
include('../components/connect.php');
include('../components/shopkeeper_header.php');

if (isset($_POST['submit_profile'])) {
    $shopkeeper_id = $_SESSION['shopkeeper_id'];
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $shopkeeper_name = $_POST['shopkeeper_name'];
    $shop_name = $_POST['shop_name'];
    $address = $_POST['address'];
    $details = $_POST['details'];

    if ($_FILES['new_image']['size'] > 0) {
        $new_image = $_FILES['new_image']['name'];
        $new_image = filter_var($new_image, FILTER_SANITIZE_STRING);
        $new_image_tmp_name = $_FILES['new_image']['tmp_name'];
        $new_image_folder = '../uploaded_img/' . $new_image;

        

        // Update shop information with the new image
        $update_shop_query = "UPDATE shopkeeper_ac SET email = '$email', shopkeeper_name = '$shopkeeper_name', shop_name = '$shop_name', address = '$address', details = '$details', image = '$new_image' WHERE id = '$shopkeeper_id'";
        $update = mysqli_query($conn, $update_shop_query);
        move_uploaded_file($new_image_tmp_name, $new_image_folder);
        //unlink old image
        $old_image = $_POST['old_image'];
        $image_path = '../uploaded_img/' . $old_image;
        // Check if the file exists before attempting to delete it
        if (file_exists($image_path)) {
            unlink($image_path);
        }

    } else {
        // Update shop information without changing the image
        $update_shop_query = "UPDATE shopkeeper_ac SET email = '$email', shopkeeper_name = '$shopkeeper_name', shop_name = '$shop_name', address = '$address', details = '$details' WHERE id = '$shopkeeper_id'";
        $update = mysqli_query($conn, $update_shop_query);
    }

    //update shop_name in products table
    $sname = "UPDATE products SET shop_name = '$shop_name' WHERE shop_id = '$shopkeeper_id'";
    $execute = mysqli_query($conn, $sname);

    $message = "Shop information updated successfully";
    // header('location: update_account.php');
    // }
}


if (isset($_POST['submit_password'])) {
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    if ($pass != $cpass) {
        $message[] = "Confirm Password not matched!";
    } else {
        $pass = sha1($pass);
        $update_profile = "UPDATE shopkeeper_ac SET password = '$pass' WHERE id = '$shopkeeper_id'";
        $update = mysqli_query($conn, $update_profile);
        $message = "Shop Password updated successfully";
    }
}
?>


<main id="main" class="main">
    <div class="pagetitle">

        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <?php
    // access id from above id var defined on php code.
    $shopkeeper_query = "SELECT * FROM shopkeeper_ac WHERE id = '$shopkeeper_id'";
    $shopkeeper_query_execute = mysqli_query($conn, $shopkeeper_query);
    $fetch = mysqli_fetch_assoc($shopkeeper_query_execute);
    ?>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="../uploaded_img/<?= $fetch['image']; ?>" alt="Profile" class="rounded-circle profile-img">
                        <h2><?php echo $fetch['shopkeeper_name']; ?></h2>
                        <h6><?php echo $fetch['shop_name']; ?></h6>
                        <p><?php echo $fetch['email']; ?></p>
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
                                <form action="" method="POST" enctype="multipart/form-data">

                                    <?php
                                    if (isset($message)) {
                                        echo '
                                        <div class="alert alert-success text-center">
                                        <?php                         
                                        <span>' . $message . '</span>
                                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                                        </div>
                                        ';
                                    }
                                    ?>
                                    <!-- hidden input - to access old img -->
                                    <input type="hidden" name="old_image" value="<?= $fetch['image'] ?>">

                                    <div class="row mb-3 ">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="../uploaded_img/<?= $fetch['image']; ?>" alt="Profile" class="profile-img">
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Shopkeeper Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="shopkeeper_name" type="text" class="form-control" required id="shopkeeperName" value="<?= $fetch['shopkeeper_name'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Shop Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="shop_name" type="text" class="form-control" required id="shopkeeperName" value="<?= $fetch['shop_name'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Address</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="email" class="form-control" required id="shopkeeperName" value="<?= $fetch['email'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Shop Image</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="new_image" type="file" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp" id="shopkeeperName">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Shop Address</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="address" type="text" class="form-control" id="shopkeeperName" value="<?= $fetch['address'] ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Shop Details</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="details" type="text" class="form-control" id="shopkeeperName" value="<?= $fetch['details'] ?>">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary" name="submit_profile">Save Changes</button>
                                        </div>
                                    </div>


                                </form>

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form action="" method="post">
                                    <?php
                                    if (isset($message)) {
                                        echo '
                                                <div class="alert alert-danger text-center">
                                                <?php                         
                                                <span>' . $message . '</span>
                                                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                                                </div>
                                            ';
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
include('../components/shopkeeper_footer.php');
?>