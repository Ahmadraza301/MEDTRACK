<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
}
$page_title = 'Admin Home - NearBy ShopWay';
include('../components/connect.php');
include('../components/admin_header.php');


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_admin = "DELETE FROM shopkeeper_ac WHERE id = ?";
    $stmt = $conn->prepare($delete_admin);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
}

?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item">Shops</li>
            </ol>
        </nav>
    </div>

    <!-- table -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Shop Details</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Shop Name</th>
                                    <th scope="col">Shopkeeper Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">No of Products</th>
                                    <td scope="col">Action</td>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                // Check if the search query is in the session
                                if (isset($_SESSION["search_query"])) {
                                    $search_query = $_SESSION["search_query"];
                                    // Perform a database query to search for matching records
                                    $sql = "SELECT * FROM shopkeeper_ac WHERE shop_name LIKE ?";
                                    $stmt = $conn->prepare($sql);
                                    // Add wildcard '%' for partial matching
                                    $searchTerm = "%$search_query%";
                                    $stmt->bind_param("s", $searchTerm);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                } else {
                                    $select_admin = "SELECT * FROM shopkeeper_ac";
                                    $stmt = $conn->prepare($select_admin);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                }
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>

                                        <tr>
                                            <th scope="row"><?php echo $row['id']; ?></th>
                                            <td><?php echo $row['shop_name']; ?></th>
                                            <td><?php echo $row['shopkeeper_name']; ?></td>
                                            <td><?php echo $row['email']; ?></td>
                                            <td><?php
                                                $name = $row['shop_name'];
                                                $no_query = "SELECT COUNT(*) as count FROM products WHERE shop_name = '$name'";
                                                $no_result = mysqli_query($conn, $no_query);
                                                
                                                if (!$no_result) {
                                                    die("Query failed: " . mysqli_error($conn));
                                                }
                                                
                                                $fetch = mysqli_fetch_assoc($no_result);
                                                echo $fetch['count'];
                                                

                                                ?></td>
                                            <td>

                                                <a href="products.php?delete=<?= $row['id']; ?>" onclick="return confirm('delete this product?')"><button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<p class="text-align-center">No products available!</p>';
                                }
                                if (isset($_SESSION["search_query"])) {
                                    // Clear the search query from the session to prevent it from being used again
                                    unset($_SESSION["search_query"]);
                                }
                                ?>

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->


<?php
include('../components/admin_footer.php');
?>