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

    if ($delete_id == $_SESSION['admin_id']) {
        $delete_admin = "DELETE FROM admin_ac WHERE id = ?";
        $stmt = $conn->prepare($delete_admin);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        session_destroy();
        header('location: login.php');
    } else {
        $delete_admin = "DELETE FROM admin_ac WHERE id = ?";
        $stmt = $conn->prepare($delete_admin);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
}

?>



<main id="main" class="main">

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item">Admins</li>
            </ol>
        </nav>
    </div>

    <!-- table -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Admin Details</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                // Check if the search query is in the session
                                if (isset($_SESSION["search_query"])) {
                                    $search_query = $_SESSION["search_query"];
                                    // Perform a database query to search for matching records
                                    $sql = "SELECT * FROM admin_ac WHERE name LIKE ?";
                                    $stmt = $conn->prepare($sql);
                                    // Add wildcard '%' for partial matching
                                    $searchTerm = "%$search_query%";
                                    $stmt->bind_param("s", $searchTerm);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                } else {
                                    $select_admin = "SELECT * FROM admin_ac";
                                    $stmt = $conn->prepare($select_admin);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                }

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <th scope="row"><?php echo $row['id']; ?></th>
                                            <td><?php echo $row['name']; ?></th>
                                            <td> <!-- admin-active or not -->
                                                <?php
                                                if ($_SESSION['admin_id'] == $row['id']) {
                                                    echo 'Active';
                                                } else {
                                                    echo 'Inactive';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="update_account.php?update=<?= $row['id']; ?>"><button class="btn btn-success">Update</button></a>
                                                <!-- pass id with update_profile link. -->
                                                <a href="admins.php?delete=<?= $row['id']; ?>" onclick="return confirm('delete this account?')"><button class="btn btn-danger">Delete</button></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<p class="text-align-center">No accounts available!</p>';
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