<?php
$admin_id = $_SESSION['admin_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["query"])) {
    // Get the search query from the form
    $searchQuery = $_POST["query"];

    // Store the search query in a session variable
    $_SESSION["search_query"] = $searchQuery;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo $page_title; ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <!-- <link href="../assets/img/logo.png" rel="icon"> -->
    <link href="../assets/img/trolley.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/admin_style.css" rel="stylesheet">


</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <!-- Logo -->
        <div class="d-flex align-items-center justify-content-between">
            <a href="../admin/home.php" class="logo d-flex align-items-center">
                <img src="../assets/img/trolley.png" alt="">
                <span class="d-none d-lg-block">Admin Panel</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <!-- Search Icon -->
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">

                    <!-- Messages Icon -->
                    <a class="nav-link nav-icon" href="../admin/feedback.php" data-bs-toggle="dropdown">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="badge bg-success badge-number">3</span>
                    </a>

                    <!-- Messages Dropdown Items -->
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                        <li class="dropdown-header">
                        
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="../assets/img/mess-1.jpg" alt="" class="rounded-circle">
                                
                            </a>
                        

                    </ul>

                </li>

                <!-- Profile Nav -->
                <li class="nav-item dropdown pe-3">

                    <?php
                    $select_profile = "SELECT * FROM admin_ac WHERE id = '$admin_id'";
                    $profile = mysqli_query($conn, $select_profile);
                    $fetch = mysqli_fetch_assoc($profile);
                    ?>

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="../assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $fetch['name']; ?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $fetch['name']; ?></h6>
                            <span>Web Developer</span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="home.php">
                                <i class="bi bi-house-door"></i>
                                <span>Home</span>
                            </a>

                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="update_account.php">
                                <i class="bi bi-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="register.php">
                                <i class="bi bi-person-plus"></i>
                                <span>Register Admin</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php" onclick="return confirm('logout from the website?');">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>

    </header>


    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="home.php">
                    <i class="bi bi-grid"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="update_account.php">
                    <i class="bi bi-person-circle"></i>
                    <span>My Account</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="admins.php">
                    <i class="bi bi-people-fill"></i>
                    <span>Admins</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="products.php">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="shops.php">
                    <i class="bi bi-shop-window"></i>
                    <span>Shops</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="category.php">
                    <i class="bi bi-list-ul"></i>
                    <span>Category</span>
                </a>
            </li>
        </ul>
    </aside>
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