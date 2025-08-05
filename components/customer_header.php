<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;700&display=swap" rel="stylesheet" />

	<link href="./assets/img/trolley.png" rel="icon">


	<!-- Carousel -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/css/glide.core.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.4.1/css/glide.theme.min.css">
	<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

	<!--StyleSheet -->
	<link rel="stylesheet" href="./assets/css/styles.css" />
	<!-- <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->

	<title>Medicines - MedTrack</title>

</head>

<body>

	<!-- 
		Header 
	-->
	<header id="header" class="header">
		<div class="navigation">
			<div class="container">
				<nav class="nav">
					<div class="nav__hamburger">
						<svg>
							<use xlink:href="./assets/img/sprite.svg#icon-menu"></use>
						</svg>
					</div>
					<div class="">
						<a href="index.php" class="scroll-link">
							<img src="./assets/img/trolley.png" alt="" style="height:40px;">
						</a>
					</div>

					<div class="nav__logo">
						<a href="index.php" class="scroll-link">
							MedTrack
						</a>
					</div>

					<div class="nav__menu">
						<div class="menu__top">
							<span class="nav__category">MedTrack</span>
							<a href="#" class="close__toggle">
								<svg>
									<use xlink:href="./assets/img/sprite.svg#icon-cross"></use>
								</svg>
							</a>
						</div>
						<ul class="nav__list">
							<li class="nav__item">
								<a href="index.php" class="hover" style="color: #222222; font-size:14px; padding:16px 0 16px;">HOME</a>
							</li>
							<br>
							<li class="nav__item">
								<a href="products.php" target="_blank" class="hover" style="color: #222222; font-size:14px; padding:16px 0 16px;">PRODUCTS</a>
							</li>
							<br>
							<li class="nav__item">
								<a href="shop.php" target="_blank" class="hover" style="color: #222222; font-size:14px; padding:16px 0 16px;">SHOPS</a>
							</li>
							<br>
							<li class="nav__item">
								<a href="map.php?show=<?php echo $row['id']; ?>" target="_blank" class="hover" style="color: #222222; font-size:14px; padding:16px 0 16px;">MAP</a>
							</li>
							<br>
							<li class="nav__item">
								<a href="contact.php" target="_blank" class="hover" style="color: #222222; font-size:14px; padding:16px 0 16px;">CONTACT</a>
							</li>
						</ul>
					</div>


					<div class="nav__icons">
						<form action="" method="GET">
							<div class="icon__item over" style="border:none;">
								<input type="text" name="search_data" id="search_data" placeholder="product name">
								<button type="submit" name="search_data_btn"><svg class="icon__search search_data">
										<use xlink:href="./assets/img/sprite.svg#icon-search"></use>
									</svg>
								</button>
							</div>
						</form>
						<a href="./shopkeeper/login.php" class="icon__item" id="login-btn">
							<svg class="icon__user">
								<use xlink:href="./assets/img/sprite.svg#icon-user"></use>
							</svg>
						</a>

						<!-- <a href="#" class="icon__item search_data_btn" id="search_data">
							<svg class="icon__search search_data">
								<use xlink:href="./assets/img/sprite.svg#icon-search"></use>
							</svg>
						</a> -->



						<a href="#" class="icon__item" id="cart-btn">
							<svg class="icon__cart">
								<use xlink:href="./assets/img/sprite.svg#icon-shopping-basket"></use>
							</svg>
							<span id="cart__total">3</span>
						</a>
					</div>
				</nav>
			</div>
		</div>





	</header>
	<!-- End Header -->