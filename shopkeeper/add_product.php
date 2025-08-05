<?php

session_start();
if (!isset($_SESSION['shopkeeper_id'])) {
    header('location: login.php');
}
$page_title = 'Shopkeeper Add Product - NearBy ShopWay';
include('../components/connect.php');
include('../components/shopkeeper_header.php');
$id = $_SESSION['shopkeeper_id'];

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

    $select_product = "SELECT * FROM products WHERE name = '$name'";
    $select = mysqli_query($conn, $select_product);
    if (mysqli_num_rows($select) > 0) {
        $message = 'Product already exist!';
    } else {

        //save image
        $image1 = $_FILES['image1']['name'];
        $imag1e = filter_var($image1, FILTER_SANITIZE_STRING);
        $image1_size = $_FILES['image1']['size'];
        $image1_tmp_name = $_FILES['image1']['tmp_name'];
        $image1_folder = '../uploaded_img/' . $image1;


        $image2 = $_FILES['image2']['name'];
        $image2 = filter_var($image2, FILTER_SANITIZE_STRING);
        $image2_size = $_FILES['image2']['size'];
        $image2_tmp_name = $_FILES['image2']['tmp_name'];
        $image2_folder = '../uploaded_img/' . $image2;


        $image3 = $_FILES['image3']['name'];
        $image3 = filter_var($image3, FILTER_SANITIZE_STRING);
        $image3_size = $_FILES['image3']['size'];
        $image3_tmp_name = $_FILES['image3']['tmp_name'];
        $image3_folder = '../uploaded_img/' . $image3;



        //fetch shop-detail
        $shop_details = "SELECT * FROM shopkeeper_ac WHERE id = '$id'";
        $shop = mysqli_query($conn, $shop_details);
        $fetch = mysqli_fetch_assoc($shop);
        $shop_name = $fetch['shop_name'];
        $shop_id = $fetch['id'];

        $insert_product = "INSERT INTO products(name,category,price,stock,details,image_01,image_02,image_03,shop_name,shop_id) VALUES ('$name','$category','$price','$quantity','$details','$image1','$image2','$image3','$shop_name','$shop_id')";
        $insert = mysqli_query($conn, $insert_product);
        move_uploaded_file($image1_tmp_name, $image1_folder);
        move_uploaded_file($image2_tmp_name, $image2_folder);
        move_uploaded_file($image3_tmp_name, $image3_folder);

        $message = "Product Added Successfully";
        // $_SESSION['message'] = "Product Added successfully";
        // header('location:products.php');
    }
}

?>


<main id="main" class="main">
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                <li class="breadcrumb-item active">Add Product</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">

        <div class="card">
            <div class="card-body">
                <div class="container mt-5">
                    <form class="row g-3" action="" method="post" enctype="multipart/form-data">
                        <h5 class="card-title">Add Product </h5>

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
                        <div class="col-md-12">
                            <label>Product Name</label>
                            <input class="form-control" type="text" required name="name" placeholder="name" maxlength="30">
                        </div>
                        <div class="col-md-6">
                            <label>Disease</label>
                            <input class="form-control" type="text" required name="category" placeholder="category" maxlength="30">
                        </div>


                        <div class="col-md-6">
                            <label>Image 01</label> <br>
                            <input type="file" name="image1" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
                        </div>
                        <div class="col-md-6">
                            <label>Image 02</label> <br>
                            <input type="file" accept="image/jpg, image/jpeg, image/png, image/webp" name="image2" required>
                        </div>
                        <div class="col-md-6">
                            <label>Image 03</label> <br>
                            <input type="file" name="image3" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
                        </div>
                        <div class="col-md-6">
                            <label>Price(&#8377;)</label> <br>
                            <input type="number" min="0" class="box" required max="9999999999" placeholder="price" onkeypress="if(this.value.length == 10) return false;" name="price">
                        </div>
                        <div class="col-md-6">
                            <label>Quantity</label> <br>
                            <input type="number" min="0" class="box" required max="1000000" placeholder="quantity" onkeypress="if(this.value.length == 6) return false;" name="quantity">
                        </div>
                        <div class="col-md-12">
                            <label>Product Details</label> <br>
                            <input class="form-control" type="text" name="details" required placeholder="shop details">
                        </div>
                        <div class="button col-md-6"">
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