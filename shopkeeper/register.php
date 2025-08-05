<?php

session_start();
// if (!isset($_SESSION['shopkeeper_id'])) {
//     header('location: login.php');
// }
// $page_title = 'shopkeeper register - NearBy ShopWay';
// include('../components/shopkeeper_header.php');
include('../components/connect.php');

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
    $shopkeeper_name = $_POST['shopkeeper_name'];
    $shop_name = $_POST['shop_name'];
    $address = $_POST['address'];
    $details = $_POST['details'];

    $select_shopkeeper = "SELECT * FROM shopkeeper_ac WHERE email = ?";
    $stmt = $conn->prepare($select_shopkeeper);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $message = 'Email already exist!';
    } else {
        if ($pass != $cpass) {
            $message = 'Confirm password not matched!';
        } else {

            //save image
            $image = $_FILES['image']['name'];
            $image = filter_var($image, FILTER_SANITIZE_STRING);
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = '../uploaded_img/' . $image;
            move_uploaded_file($image_tmp_name, $image_folder);


            $insert_shopkeeper = "INSERT INTO shopkeeper_ac(email, password,shop_name,shopkeeper_name,address,details,image) VALUES ('$email', '$pass','$shop_name','$shopkeeper_name','$address','$details','$image')";
            $insert = mysqli_query($conn, $insert_shopkeeper);
            move_uploaded_file($image_tmp_name, $image);
            // $compressed_image_name
            $_SESSION['message'] = "Please login now";
            header('location:login.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?"></script>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Shopkeeper Register - NearBy ShopWay</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/trolley.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/css/register.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">


</head>

<body>
    <section class="container">
        <h1 style="text-align: center;">Shopkeeper Register</h1>
        <form action="" class="form" method="post" enctype="multipart/form-data">
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
            <div class="column">
                <div class="input-box">
                    <label>Email Address</label>
                    <input class="form-control" type="email" required name="email" placeholder="email" maxlength="100" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>

                <div class="input-box">
                    <label>Shopkeeper Name</label>
                    <input class="form-control" type="text" required name="shopkeeper_name" placeholder="shopkeeper Name" maxlength="100">
                </div>
            </div>

            <div class="column">
                <div class="input-box">
                    <label>Password</label>
                    <input class="form-control" type="password" name="pass" required placeholder="password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <div class="input-box">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="cpass" required placeholder="confirm password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
            </div>

            <div class="column">
                <div class="input-box">
                    <label>Shop Name</label>
                    <input class="form-control" type="text" name="shop_name" required placeholder="shop name" maxlength="50">
                </div>
                <div class="input-box">
                    <label>Medical License</label>
                    <input type="file" accept="image/jpg, image/jpeg, image/png, image/webp" name="image" required>
                </div>
            </div>

            <div class="input-box">
                <label>Shop Address</label>
                <input class="form-control" type="text" name="address" id="location" required placeholder="shop address">
            </div>
            <div class="input-box">
                <label>Shop Details</label>
                <input class="form-control" type="text" name="details" required placeholder="shop details">
            </div>

            <div class="column">
                <button type="submit" name="submit">Register</button>
            </div>
        </form>
    </section>
    <script type="text/javascript">
    $(document).ready(function(){

        var autocomplete;
        var id = 'location';

        autocomplete = new google.maps.places.Autocomplete((document.getElementById(id)),{
            types:['geocode'],
        })
    })
</script>

</body>

</html>