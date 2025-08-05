<?php

session_start();
if (!isset($_SESSION['shopkeeper_id'])) {
	header('location: login.php');
}
$id = $_SESSION['shopkeeper_id'];
$page_title = 'Shopkeeper Home - NearBy ShopWay';
include('../components/connect.php');
include('../components/shopkeeper_header.php');


if (isset($_GET['delete'])) {
	$delete_id = $_GET['delete'];
	$delete_admin = "DELETE FROM products WHERE id = ?";
	$stmt = $conn->prepare($delete_admin);
	$stmt->bind_param("i", $delete_id);
	$stmt->execute();
	header('location:home.php');
}
?>


<main id="main" class="main">
	<div class="pagetitle">
		<h1>Dashboard</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="home.php">Home</a></li>
				<!-- <li class="breadcrumb-item active">Dashboard</li> -->
			</ol>
		</nav>
	</div>

	<!-- sections -->
	<section class="section dashboard">
		<div class="row">

			<div class="col-lg-12">
				<div class="row d-flex">

					<!-- Update Profile -->
					<div class="col-xxl-4 col-md-4 col-sm-6">
						<div class="card info-card sales-card">
							<a href="update_account.php">
								<div class="card-body">
									<h5 class="card-title">Update Account</h5>
									<div class="d-flex align-items-center">
										<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
											<i class="bi bi-person-circle"></i>
										</div>
										<div class="ps-3">
											<h6>Welcome!</h6>
											<span class="text-muted small pt-2 ps-1">Vijay</span>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>


					<?php
					$admin = "SELECT COUNT(*) AS count FROM products WHERE shop_id = '$id'";
					$admin_ex = mysqli_query($conn, $admin);
					$row = mysqli_fetch_assoc($admin_ex);
					$no = $row['count'];
					?>
					<div class="col-xxl-4 col-md-4 col-sm-6">
						<div class="card info-card sales-card">
							<a href="products.php">
								<div class="card-body">
									<h5 class="card-title">See Products</h5>
									<div class="d-flex align-items-center">
										<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
											<i class="bi bi-shop-window"></i>
										</div>
										<div class="ps-3">
											<h6><?= $no ?></h6>
											<span class="text-muted small pt-2 ps-1">Total Products</span>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>



					<div class="col-xxl-4 col-md-4 col-sm-6">
						<div class="card info-card sales-card">
							<a href="add_product.php">
								<div class="card-body">
									<h5 class="card-title">Add Product</h5>
									<div class="d-flex align-items-center">
										<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
											<i class="bi bi-shop-window"></i>
										</div>
										<div class="ps-3">
											<h6><?= $no ?></h6>
											<span class="text-muted small pt-2 ps-1">Total Products</span>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>

					<!-- category -->
					<?php
					$admin = "SELECT COUNT(distinct category) AS count FROM products WHERE shop_id = '$id'";
					$admin_ex = mysqli_query($conn, $admin);
					$row = mysqli_fetch_assoc($admin_ex);
					$no = $row['count'];
					?>
					<div class="col-xxl-4 col-md-4 col-sm-6">
						<div class="card info-card sales-card">
							<a href="category.php">
								<div class="card-body">
									<h5 class="card-title">See Categories</h5>
									<div class="d-flex align-items-center">
										<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
											<i class="bi bi-list-ul"></i>
										</div>
										<div class="ps-3">
											<h6><?= $no ?></h6>
											<span class="text-muted small pt-2 ps-1">Total Categories</span>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		</div>




		<section class="section">
			<div class="row">
				<div class="col-lg-12">

					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Product Details</h5>

							<!-- Table with stripped rows -->
							<table class="table datatable">
								<thead>
									<tr>
										<th scope="col">ID</th>
										<th scope="col">Product</th>
										<th scope="col">Price</th>
										<th scope="col">Category</th>
										<th scope="col">Shop</th>
										<th scope="col">Shop ID</th>
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
										$sql = "SELECT * FROM products WHERE name LIKE ?";
										$stmt = $conn->prepare($sql);
										// Add wildcard '%' for partial matching
										$searchTerm = "%$search_query%";
										$stmt->bind_param("s", $searchTerm);
										$stmt->execute();
										$result = $stmt->get_result();
									} else {
										$select_admin = "SELECT * FROM products WHERE shop_id = '$id'";
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
												<td><?php echo $row['price']; ?></td>
												<td><?php echo $row['category']; ?></td>
												<td><?php echo $row['shop_name']; ?></td>
												<td><?php echo $row['shop_id']; ?></td>
												<td><?php echo $row['stock']; ?></td>
												<td>
													<a href="update_product.php?update=<?= $row['id']; ?>"><i class="bi bi-arrow-up-circle-fill"></i></a>
													<!-- pass id with update_profile link. -->
													<a href="products.php?delete=<?= $row['id']; ?>" onclick="return confirm('delete this product?')"><i class="bi bi-trash-fill"></i></a>
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
include('../components/shopkeeper_footer.php');
?>