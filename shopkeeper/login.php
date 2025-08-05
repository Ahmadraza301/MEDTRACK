<?php

session_start();
// $page_title = 'shopkeeper Login - NearBy ShopWay';
// include('../components/shopkeeper_header.php');
include('../components/connect.php');

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_shopkeeper = "SELECT * FROM shopkeeper_ac WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($select_shopkeeper);
    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();

    $res = $stmt->get_result();
    $fetch = $res->fetch_assoc();

    if ($res->num_rows > 0) {
        $_SESSION['shopkeeper_id'] = $fetch['id'];
        $_SESSION['shopkeeper_name'] = $fetch['shopkeeper_name'];
        $_SESSION['shop_name'] = $fetch['shop_name'];
        header('location:home.php');
        // header('location:register.php');
    } else {
        $message = 'incorrect email or password!';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Shopkeeper Login - NearBy ShopWay</title>
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
    <link href="../assets/css/shopkeeper_style.css" rel="stylesheet">
    <link href="../assets/css/login-signup.css" rel="stylesheet">


</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <form action="" method="POST">
                    <h2 class="text-center">shopkeeper Login</h2>
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
                    if (isset($_SESSION['message'])) {
                        echo '
                        <div class="alert alert-danger text-center">
                           <span>' . $_SESSION['message'] . '</span>
                           <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                        </div>
                        ';
                        // Clear the message from the session to prevent displaying it again
                        unset($_SESSION['message']);
                        // Clear the message from the session to prevent displaying it again
                        unset($_SESSION['message']);
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="email" required name="email" placeholder="Email" maxlength="30" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <br>
                    <div class="form-group">
                        <input class="form-control" type="password" name="pass" required placeholder="Password" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <br>
                    <p class="text-center"><a href="">forgot password?</a></p>
                    <p class="text-center">Not yet registered? <a href="register.php">Register now!</a></p>
                    <p class="text-center">Not a shopkeeper?<a href="../admin/login.php">Admin Login!</a></p>
                    <div class="d-flex">
                        <div class="form-group col-10">
                            <input class="form-control button" type="submit" name="submit" value="Login">
                        </div>
                        <div class="form-group col-2 text-center">
                            <a href="../index.php" class="form-control button"><i class="bi bi-house-door"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>