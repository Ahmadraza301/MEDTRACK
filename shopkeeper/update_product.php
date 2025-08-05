<?php

session_start();
if (!isset($_SESSION['shopkeeper_id'])) {
    header('location: login.php');
}
$page_title = 'Shopkeeper Update Products - NearBy ShopWay';
include('../components/connect.php');
include('../components/shopkeeper_header.php');
// $id = $_SESSION['shopkeeper_id'];
$id = $_GET['update'];

if (isset($_POST['submit'])) {


    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    if ($_FILES['nimage1']['size'] > 0 || $_FILES['nimage2']['size'] > 0 || $_FILES['nimage3']['size'] > 0) {
        //save image
        $nimage1 = $_FILES['nimage1']['name'];
        $nimage = filter_var($nimage1, FILTER_SANITIZE_STRING);
        $nimage1_size = $_FILES['nimage1']['size'];
        $nimage1_tmp_name = $_FILES['nimage1']['tmp_name'];
        $nimage1_folder = '../uploaded_img/' . $nimage1;


        $nimage2 = $_FILES['nimage2']['name'];
        $nimage2 = filter_var($nimage2, FILTER_SANITIZE_STRING);
        $nimage2_size = $_FILES['nimage2']['size'];
        $nimage2_tmp_name = $_FILES['nimage2']['tmp_name'];
        $nimage2_folder = '../uploaded_img/' . $nimage2;


        $nimage3 = $_FILES['nimage3']['name'];
        $nimage3 = filter_var($nimage3, FILTER_SANITIZE_STRING);
        $nimage3_size = $_FILES['nimage3']['size'];
        $nimage3_tmp_name = $_FILES['nimage3']['tmp_name'];
        $nimage3_folder = '../uploaded_img/' . $nimage3;

        // Update shop information with the new image
        $update_shop_query = "UPDATE products SET name = '$name',category='$category',price='$price',stock='$quantity',details='$details' image_01='$nimage1',image_02='$nimage2',image_03='$nimage3' WHERE id='$id'";
        $update = mysqli_query($conn, $update_shop_query);
        move_uploaded_file($nimage1_tmp_name, $nimage1_folder);
        move_uploaded_file($nimage2_tmp_name, $nimage2_folder);
        move_uploaded_file($nimage3_tmp_name, $nimage3_folder);
        //unlink old image
        $oimage1 = $_POST['oimage1'];
        $oimage2 = $_POST['oimage2'];
        $oimage3 = $_POST['oimage3'];
        $image_path1 = '../uploaded_img/' . $oimage1;
        $image_path2 = '../uploaded_img/' . $oimage2;
        $image_path3 = '../uploaded_img/' . $oimage3;
        // Check if the file exists before attempting to delete it
        if (file_exists($image_path1)) {
            unlink($image_path1);
        }
        if (file_exists($image_path2)) {
            unlink($image_path2);
        }
        if (file_exists($image_path3)) {
            unlink($image_path3);
        }
    } else {
        // Update shop information without changing the image
        $update_shop_query = "UPDATE products SET name = '$name',category='$category',price='$price',stock='$quantity',details='$details' WHERE id='$id'";
        $update = mysqli_query($conn, $update_shop_query);
    }
    $message = "Product information updated successfully";
    // header('location: update_account.php');
    // }
}




?>


<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item active">Update Product</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">


        <?php
        // access id from above id var defined on php code.
        $shopkeeper_query = "SELECT * FROM products WHERE id = '$id'";
        $shopkeeper_query_execute = mysqli_query($conn, $shopkeeper_query);
        $fetch = mysqli_fetch_assoc($shopkeeper_query_execute);
        ?>
        <div class="card">
            <div class="card-body">
                <div class="container mt-5">
                    <form class="row g-3" action="" method="post" enctype="multipart/form-data">
                        <h5 class="card-title">Update Product </h5>

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
                        <span>
                            <input type="hidden" name="oimage1" value="<?= $fetch['image_01'] ?>">
                            <input type="hidden" name="oimage2" value="<?= $fetch['image_02'] ?>">
                            <input type="hidden" name="oimage3" value="<?= $fetch['image_03'] ?>">
                        </span>

                        <div class="image-container">
                            <div class="main-image">
                                <img src="../uploaded_img/<?= $fetch['image_01']; ?>" alt="">
                            </div>
                            <div class="sub-image">
                                <img src="../uploaded_img/<?= $fetch['image_01']; ?>" alt="">
                                <img src="../uploaded_img/<?= $fetch['image_02']; ?>" alt="">
                                <img src="../uploaded_img/<?= $fetch['image_03']; ?>" alt="">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <label>Product Name</label>
                            <input class="form-control" type="text" required name="name" placeholder="name" maxlength="30" value="<?= $fetch['name'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Disease</label>
                            <input class="form-control" type="text" required name="category" placeholder="category" maxlength="30" value="<?= $fetch['category'] ?>">
                        </div>


                        <div class="col-md-6">
                            <label>Image 01</label> <br>
                            <input type="file" name="nimage1" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" value="<?= $fetch['image_01'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Image 02</label> <br>
                            <input type="file" accept="image/jpg, image/jpeg, image/png, image/webp" name="nimage2" value="<?= $fetch['image_02'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Image 03</label> <br>
                            <input type="file" name="nimage3" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" value="<?= $fetch['image_03'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Price(&#8377;)</label> <br>
                            <input type="number" min="0" class="box" required max="9999999999" placeholder="price" onkeypress="if(this.value.length == 10) return false;" name="price" value="<?= $fetch['price'] ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Quantity</label> <br>
                            <input type="number" min="0" class="box" required max="1000000" placeholder="quantity" onkeypress="if(this.value.length == 6) return false;" name="quantity" value="<?= $fetch['stock'] ?>">
                        </div>
                        <div class="col-md-12">
                            <label>Product Details</label> <br>
                            <input class="form-control" type="text" name="details" required placeholder="shop details" value="<?= $fetch['details'] ?>">
                        </div>
                        <div class="button col-md-6">
                            <label for="">
                                <input class=" btn-success" type="submit" name="submit" style="background: blue; color:white">
                            </label>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </section>
</main><!-- End #main -->

<?php
include('../components/shopkeeper_footer.php');
?>