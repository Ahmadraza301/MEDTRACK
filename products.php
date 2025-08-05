<?php

$page_title = 'MedTrack - Products';
include('./components/connect.php');
include('./components/customer_header.php');

?>

<main id="main">
	<div class="container">
		<!-- Collection -->
		<section id="collection" class="section collection">
			<div class="collection__container" data-aos="fade-up" data-aos-duration="1200">
				<img class="collection_02 collection__box" src="./assets/img/2.jpg" alt="">
				<img class="collection_01 collection__box" src="./assets/img/5.jpg" alt="">
		</section>


		<!-- All Products -->
		<section class="section latest__products" id="all">
			<div class="title__container">
				<div class="section__title active">
					<span class="dot"></span>
					<h1 class="primary__title">All Products</h1>
				</div>
			</div>
			<div class="my_container" data-aos="fade-up" data-aos-duration="1200" style="display: flex; flex-direction:row; align-items: center; justify-content: center;">
				<div class="glide" id="glide_2">
					<div class="glide__track" data-glide-el="track">
						<ul class="glide__slides latest-center">
							<?php
							if (isset($_GET['search_data_btn'])) {

								$search_query = $_GET['search_data'];
								$sql = "SELECT * FROM products WHERE name LIKE ?";
								$stmt = $conn->prepare($sql);
								// Add wildcard '%' for partial matching
								$searchTerm = "%$search_query%";
								$stmt->bind_param("s", $searchTerm);
								$stmt->execute();
								$result = $stmt->get_result();
								echo "<a href='#latest'></a>";
							} else {

								$sql = "SELECT * FROM `products` ORDER BY RAND()";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$result = $stmt->get_result();
							}
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
							?>

									<li class="glide__slide">
										<div class="product">
											<div class="product__header">
												<img src="uploaded_img/<?php echo $row['image_01']; ?>" alt="product">
											</div>
											<div class="product__footer">
												<h3 style="color: blueviolet;"><?php echo $row['name']; ?></h3>
												<br>
												<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
													<p style="color: green;">&#8377;<?php echo $row['price']; ?></p>
													<p style="color: red;"><?php echo $row['stock']; ?> Quantity left</p>
												</div>

												<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
													<p><?php echo $row['shop_name']; ?></p>
													<a href='map.php?shop=<?php echo $row['shop_id']; ?>' target='_blank'><i class='bi bi-geo-alt-fill'></i></a>
												</div>

												<a href="prod_desc.php?show=<?php echo $row['id']; ?>"><button type="submit" class="product__btn">Details</button></a>
												<ul>
													<li>
														<a data-tip="Quick View" data-place="left" href="prod_desc.php">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-eye"></use>
															</svg>
														</a>
													</li>
													<li>
														<a data-tip="Add To Wishlist" data-place="left" href="map.php">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-location" style="height: 5px;"></use>
															</svg>
														</a>
													</li>
													<li>
														<a data-tip="Add To Compare" data-place="left" href="#">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-loop2"></use>
															</svg>
														</a>
													</li>
												</ul>
											</div>
										</div>
										<div style="margin-right: 5vw;"></div>
									</li>
							<?php
								}
							}
							$result = null;
							?>
						</ul>
					</div>
					<!-- arrows -->
					<div class="glide__arrows" data-glide-el="controls">
						<button class="glide__arrow glide__arrow--left" data-glide-dir="<">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-arrow-left2"></use>
							</svg>
						</button>
						<button class="glide__arrow glide__arrow--right" data-glide-dir=">">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-arrow-right2"></use>
							</svg>
						</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div class="container">
		<!-- New Products -->
		<section class="section latest__products" id="latest">
			<div class="title__container">
				<div class="section__title active">
					<span class="dot"></span>
					<h1 class="primary__title">New Products</h1>
				</div>
			</div>
			<div class="my_container" data-aos="fade-up" data-aos-duration="1200" style="display: flex; flex-direction:row; align-items: center; justify-content: center;">
				<div class="glide" id="glide_2">
					<div class="glide__track" data-glide-el="track">
						<ul class="glide__slides latest-center">
							<?php

							$sql = "SELECT * FROM `products` ORDER BY id DESC";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$result = $stmt->get_result();

							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
							?>

									<li class="glide__slide">
										<div class="product">
											<div class="product__header">
												<img src="uploaded_img/<?php echo $row['image_01']; ?>" alt="product">
											</div>
											<div class="product__footer">
												<h3 style="color: blueviolet;"><?php echo $row['name']; ?></h3>
												<br>
												<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
													<p style="color: green;">&#8377;<?php echo $row['price']; ?></p>
													<p style="color: red;"><?php echo $row['stock']; ?> Quantity left</p>
												</div>

												<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
													<p><?php echo $row['shop_name']; ?></p>
													<a href='map.php?shop=<?php echo $row['shop_id']; ?>' target='_blank'><i class='bi bi-geo-alt-fill'></i></a>
												</div>

												<a href="prod_desc.php?show=<?php echo $row['id']; ?>"><button type="submit" class="product__btn">Details</button></a>
												<ul>
													<li>
														<a data-tip="Quick View" data-place="left" href="prod_desc.php">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-eye"></use>
															</svg>
														</a>
													</li>
													<li>
														<a data-tip="Add To Wishlist" data-place="left" href="map.php">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-location" style="height: 5px;"></use>
															</svg>
														</a>
													</li>
													<li>
														<a data-tip="Add To Compare" data-place="left" href="#">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-loop2"></use>
															</svg>
														</a>
													</li>
												</ul>
											</div>
										</div>
										<div style="margin-right: 5vw;"></div>
									</li>
							<?php
								}
							}
							$result = null;
							?>
						</ul>
					</div>
					<!-- arrows -->
					<div class="glide__arrows" data-glide-el="controls">
						<button class="glide__arrow glide__arrow--left" data-glide-dir="<">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-arrow-left2"></use>
							</svg>
						</button>
						<button class="glide__arrow glide__arrow--right" data-glide-dir=">">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-arrow-right2"></use>
							</svg>
						</button>
					</div>
				</div>
			</div>
		</section>
	</div>


	<div class="container">
		<!-- Most Viewed Products -->
		<section class="section latest__products" id="trending">
			<div class="title__container">
				<div class="section__title active">
					<span class="dot"></span>
					<h1 class="primary__title">Most Viewed Product</h1>
				</div>
			</div>
			<div class="my_container" data-aos="fade-up" data-aos-duration="1200" style="display: flex; flex-direction:row; align-items: center; justify-content: center;">
				<div class="glide" id="glide_2">
					<div class="glide__track" data-glide-el="track">
						<ul class="glide__slides latest-center">
							<?php


							$sql = "SELECT * FROM `products` ORDER BY id DESC";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$result = $stmt->get_result();

							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
							?>

									<li class="glide__slide">
										<div class="product">
											<div class="product__header">
												<img src="uploaded_img/<?php echo $row['image_01']; ?>" alt="product">
											</div>
											<div class="product__footer">
												<h3 style="color: blueviolet;"><?php echo $row['name']; ?></h3>
												<br>
												<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
													<p style="color: green;">&#8377;<?php echo $row['price']; ?></p>
													<p style="color: red;"><?php echo $row['stock']; ?> Quantity left</p>
												</div>

												<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
													<p><?php echo $row['shop_name']; ?></p>
													<a href='map.php?shop=<?php echo $row['shop_id']; ?>' target='_blank'><i class='bi bi-geo-alt-fill'></i></a>
												</div>

												<a href="prod_desc.php?show=<?php echo $row['id']; ?>"><button type="submit" class="product__btn">Details</button></a>
												<ul>
													<li>
														<a data-tip="Quick View" data-place="left" href="prod_desc.php">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-eye"></use>
															</svg>
														</a>
													</li>
													<li>
														<a data-tip="Add To Wishlist" data-place="left" href="map.php">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-location" style="height: 5px;"></use>
															</svg>
														</a>
													</li>
													<li>
														<a data-tip="Add To Compare" data-place="left" href="#">
															<svg>
																<use xlink:href="./assets/img/sprite.svg#icon-loop2"></use>
															</svg>
														</a>
													</li>
												</ul>
											</div>
										</div>
										<div style="margin-right: 5vw;"></div>
									</li>
							<?php
								}
							}
							?>
						</ul>
					</div>
					<!-- arrows -->
					<div class="glide__arrows" data-glide-el="controls">
						<button class="glide__arrow glide__arrow--left" data-glide-dir="<">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-arrow-left2"></use>
							</svg>
						</button>
						<button class="glide__arrow glide__arrow--right" data-glide-dir=">">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-arrow-right2"></use>
							</svg>
						</button>
					</div>
				</div>
			</div>
		</section>
	</div>


</main>

<!-- End Main -->
<?php
include('./components/customer_footer.php');
?>