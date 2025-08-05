<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
}
$page_title = 'Admin Category - NearBy ShopWay';
include('../components/connect.php');
include('../components/admin_header.php');

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_admin = "DELETE FROM products WHERE id = ?";
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
                <li class="breadcrumb-item">Categories</li>
            </ol>
        </nav>
    </div>

    <!-- table -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Category Details</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Product ID</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                // Check if the search query is in the session
                                if (isset($_SESSION["search_query"])) {
                                    $search_query = $_SESSION["search_query"];
                                    // Perform a database query to search for matching records
                                    $sql = "SELECT * FROM products WHERE category LIKE ?";
                                    $stmt = $conn->prepare($sql);
                                    // Add wildcard '%' for partial matching
                                    $searchTerm = "%$search_query%";
                                    $stmt->bind_param("s", $searchTerm);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                } else {
                                    $select_admin = "SELECT * FROM products ORDER BY category";
                                    $stmt = $conn->prepare($select_admin);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                }
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>

                                        <tr>
                                            <td><?php echo $row['category']; ?></th>
                                            <td><?php echo $row['id']; ?></th>
                                            <td><?php echo $row['name']; ?></th>
                                            <td><?php echo $row['price']; ?></td>
                                            <td><?php echo $row['stock']; ?></td>
                                            <td>
                                                <a href="products.php?delete=<?= $row['id']; ?>" onclick="return confirm('delete this account?')"><i class="bi bi-trash-fill"></i></a>
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