<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
}
// $page_title = 'Admin register - NearBy ShopWay';
// include('../components/admin_header.php');
include('../components/connect.php');

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_admin = "SELECT * FROM admin_ac WHERE name = ?";
    $stmt = $conn->prepare($select_admin);
    $stmt->bind_param("s",$name);
    $stmt->execute();

    $res = $stmt->get_result();

    if($res->num_rows >0){
        $message = 'Username already exist!';
    } else {
        if ($pass != $cpass) {
            $message = 'Confirm password not matched!';
        } else {
            $insert_admin = "INSERT INTO admin_ac(name, password) VALUES ('$name', '$pass')";
            $insert = mysqli_query($conn, $insert_admin);
            header('location:admins.php');
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Admin Register - NearBy ShopWay</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/trolley.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/admin_style.css" rel="stylesheet">
    <link href="../assets/css/login-signup.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <form action="" method="POST">
                    <h2 class="text-center">Register Admin</h2>
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
                    <div class="form-group">
                        <input class="form-control" type="text" required name="name" placeholder="Username" maxlength="30" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <br>
                    <div class="form-group">
                        <input class="form-control" type="password" name="pass" required placeholder="Password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <br>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpass" required placeholder="Confirm Password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <br>
                    <div class="d-flex">
                        <div class="form-group col-10">
                            <input class="form-control button" type="submit" name="submit" value="Signup">
                        </div>
                        <div class="form-group col-2 text-center">
                            <a href="home.php" class="form-control button"><i class="bi bi-house-door"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>