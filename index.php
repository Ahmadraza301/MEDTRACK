<?php

$page_title = 'MedTrack - HomePage';
include('./components/connect.php');
include('./components/customer_header.php');

?>

<!-- 
		Main
	 -->
<main id="main">
	<!-- Hero -->
	<div class="hero">
		<div class="glide" id="glide_1">
			<div class="glide__track" data-glide-el="track">
				<ul class="glide__slides">
					<li class="glide__slide">
						<img src="./assets/img/1.jpg" alt="" srcset="">
					</li>
					<li class="glide__slide">
						<img src="./assets/img/7.jpg" alt="" srcset="" style="object-fit:contain;">
					</li>
					<li class="glide__slide">
						<img src="./assets/img/8.jpg" alt="" srcset="">
					</li>
				</ul>
			</div>
			<div class="glide__bullets" data-glide-el="controls[nav]">
				<button class="glide__bullet" data-glide-dir="=0"></button>
				<button class="glide__bullet" data-glide-dir="=1"></button>
				<button class="glide__bullet" data-glide-dir="=2"></button>
			</div>

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
	<div class="container">
		<!-- Collection -->
		<section id="collection" class="section collection">
			<div class="collection__container" data-aos="fade-up" data-aos-duration="1200">
					<img class="collection_02 collection__box" src="./assets/img/2.jpg" alt="">
					<img class="collection_01 collection__box" src="./assets/img/5.jpg" alt="">
		</section>

		<!-- ALl Products -->
		<section class="section latest__products" id="products">

			<div class="title__container">
				<div class="section__title active">
					<span class="dot"></span>
					<h1 class="primary__title">All Products</h1>
				</div>
			</div>
			<!-- <div class="my_container" data-aos="fade-up" data-aos-duration="1200" style="display: flex; flex-direction:row; align-items: center; justify-content: center;"> -->
			<!-- <div class="glide" id="glide_2"> -->
			<!-- <div class="glide__track" > -->

			<div class="container new" style="width: 100%;">
				<div class="facility__contianer" data-aos="fade-up" data-aos-duration="1200" style="margin-right:2vw !important;">

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
						echo "<a href='#products'></a>";
					} else {

						$sql = "SELECT * FROM `products` ORDER BY RAND()";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$result = $stmt->get_result();
					}
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
					?>
							<div class="facility__box" style="margin: 10% !important; height: 70vh;width:20vw; border:1px solid black; padding:10%;">

								<!-- <ul class="" >glide__slides latest-center -->


								<!-- <li class="">glide__slide -->
								<!-- <div class="product"> -->
								<div class="product__header">
									<!-- <div class="facility-img__container"> -->
									<img src="uploaded_img/<?php echo $row['image_01']; ?>" alt="product">
								</div>
								<div class="product__footer">
									<h3 style="color: blueviolet;"><?php echo $row['name']; ?></h3>
									<!-- <br> -->
									<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
										<p style="color: green;">&#8377;<?php echo $row['price']; ?></p>
										<p style="color: red;"><?php echo $row['stock']; ?> Quantity left</p>
									</div>

									<div style='display:flex; justify-content: space-between; padding-bottom:5px'>
										<h3><?php echo $row['shop_name']; ?></h3>
										
									</div>

									<a class="margin" href="prod_desc.php?show=<?php echo $row['id']; ?>"><button style="margin-right: 5vw;" type="submit" class="product__btn">Details</button></a>
									<ul>
										<li>
											<a data-tip="Quick View" data-place="left" href="prod_desc.php?show=<?php echo $row['id']; ?>">
												<svg>
													<use xlink:href="./assets/img/sprite.svg#icon-eye"></use>
												</svg>
											</a>
										</li>
										<li>
											<a data-tip="Add To Wishlist" data-place="left" href="map.php?show=<?php echo $row['id']; ?>">
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
								<!-- </div> -->

								<!-- </li> -->
							</div>
					<?php
						}
					}
					?>
					<!-- </ul> -->
				</div>
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
			<!-- </div> -->
			<!-- </div> -->
		</section>



		<!-- Facility Section -->
		<section class="facility__section section" id="facility">
			<div class="container">
				<div class="facility__contianer" data-aos="fade-up" data-aos-duration="1200">
					<div class="facility__box">
						<div class="facility-img__container">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-search"></use>
							</svg>
						</div>
						<p>FIND EMERGENCY & RARE MEDICINES IN YOUR NEAREST SHOPS</p>
					</div>

					<div class="facility__box">
						<div class="facility-img__container">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-location"></use>
							</svg>
						</div>
						<p>LOCATION BASED MEDICINE SEARCH</p>
					</div>

					<div class="facility__box">
						<div class="facility-img__container">
							<svg> <!-- Paper Plane -->
								<use xlink:href="./assets/img/sprite.svg#icon-balance-scale"></use>
							</svg>
						</div>
						<p>REAL TIME UPDATE OF STOCKS AND PRICES</p>
					</div>

					<div class="facility__box">
						<div class="facility-img__container">
							<svg>
								<use xlink:href="./assets/img/sprite.svg#icon-headphones"></use>
							</svg>
						</div>
						<p>24/7 SERVICE</p>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- Testimonial Section -->
	<section class="section testimonial" id="testimonial">
		<div class="testimonial__container">
			<div class="glide" id="glide_4">
				<div class="glide__track" data-glide-el="track">
					<ul class="glide__slides">
						<li class="glide__slide">
							<div class="testimonial__box">
								<div class="client__image">
									<img src="./assets/img/p1.jpg" alt="profile">
								</div>
								<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae fuga hic
									nesciunt tempore
									quibusdam consequatur eligendi unde officia ex quae.ipsum dolor sit amet
									consectetur adipisicing
									elit. Recusandae fuga hic nesciunt tempore
									quibusdam consequatur eligendi unde officia ex quae.</p>
								<div class="client__info">
									<h3>Vijay Gupta</h3>
									<span>CEO at AllInOne Pharmcy</span>
								</div>
							</div>
						</li>

						<li class="glide__slide">
							<div class="testimonial__box">
								<div class="client__image">
									<img src="./assets/img/p4.jpg" alt="profile">
								</div>
								<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae fuga hic
									nesciunt tempore
									quibusdam consequatur
									eligendi unde officia ex quae.ipsum dolor sit amet consectetur adipisicing elit.
									Recusandae fuga hic
									nesciunt tempore
									quibusdam consequatur eligendi unde officia ex quae.adipisicing elit. Recusandae
									fuga hic
									nesciunt tempore
									quibusdam consequatur eligendi unde officia ex quae.</p>
								<div class="client__info">
									<h3>Neha Malhotra</h3>
									<span>MD at Medico Solutions</span>
								</div>
							</div>

						</li>
						<li class="glide__slide">
							<div class="testimonial__box">
								<div class="client__image">
									<img src="./assets/img/p3.jpg" alt="">
								</div>
								<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae fuga hic
									nesciunt tempore
									quibusdam consequatur
									eligendi unde officia ex quae.adipisicing elit. Recusandae fuga hic
									nesciunt tempore
									quibusdam consequatur eligendi unde officia ex quae.
									hic
									nesciunt tempore
									quibusdam consequatur eligendi unde officia ex quae.</p>
								<div class="client__info">
									<h3>Jennifier Smith</h3>
									<span>MD & CEO at GlobalPharma</span>
								</div>
							</div>
						</li>
					</ul>
				</div>

				<div class="glide__bullets" data-glide-el="controls[nav]">
					<button class="glide__bullet" data-glide-dir="=0"></button>
					<button class="glide__bullet" data-glide-dir="=1"></button>
					<button class="glide__bullet" data-glide-dir="=2"></button>
				</div>
			</div>
		</div>
	</section>


</main>

<!-- End Main -->

<?php
include('./components/customer_footer.php');
?>