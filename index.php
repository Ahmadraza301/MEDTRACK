<?php
$page_title = 'MedTrack - HomePage';
include('./components/connect.php');
include('./components/customer_header.php');

// Initialize variables
$search_query = '';
$products = [];
$search_performed = false;

// Handle search functionality
if (isset($_GET['search_data_btn']) && !empty($_GET['search_data'])) {
    $search_query = trim($_GET['search_data']);
    $search_performed = true;
    
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT p.*, pc.name as category_name, s.shop_name, s.address 
            FROM products p 
            INNER JOIN product_categories pc ON p.category_id = pc.id 
            INNER JOIN shopkeeper_accounts s ON p.shop_id = s.id 
            WHERE p.name LIKE ? AND p.is_active = 1 
            ORDER BY p.name ASC";
    
    $stmt = $conn->prepare($sql);
    $search_term = "%{$search_query}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    $stmt->close();
} else {
    // Get all active products with category and shop information
    $sql = "SELECT p.*, pc.name as category_name, s.shop_name, s.address 
            FROM products p 
            INNER JOIN product_categories pc ON p.category_id = pc.id 
            INNER JOIN shopkeeper_accounts s ON p.shop_id = s.id 
            WHERE p.is_active = 1 
            ORDER BY RAND() 
            LIMIT 20";
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
}
?>

<!-- Main Content -->
<main id="main">
    <!-- Hero Section -->
    <div class="hero">
        <div class="glide" id="glide_1">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <li class="glide__slide">
                        <img src="./assets/img/1.jpg" alt="MedTrack Hero Image 1" loading="lazy">
                    </li>
                    <li class="glide__slide">
                        <img src="./assets/img/7.jpg" alt="MedTrack Hero Image 2" loading="lazy" style="object-fit:contain;">
                    </li>
                    <li class="glide__slide">
                        <img src="./assets/img/8.jpg" alt="MedTrack Hero Image 3" loading="lazy">
                    </li>
                </ul>
            </div>
            <div class="glide__bullets" data-glide-el="controls[nav]">
                <button class="glide__bullet" data-glide-dir="=0" aria-label="Go to slide 1"></button>
                <button class="glide__bullet" data-glide-dir="=1" aria-label="Go to slide 2"></button>
                <button class="glide__bullet" data-glide-dir="=2" aria-label="Go to slide 3"></button>
            </div>

            <div class="glide__arrows" data-glide-el="controls">
                <button class="glide__arrow glide__arrow--left" data-glide-dir="<" aria-label="Previous slide">
                    <svg>
                        <use xlink:href="./assets/img/sprite.svg#icon-arrow-left2"></use>
                    </svg>
                </button>
                <button class="glide__arrow glide__arrow--right" data-glide-dir=">" aria-label="Next slide">
                    <svg>
                        <use xlink:href="./assets/img/sprite.svg#icon-arrow-right2"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Collection Section -->
        <section id="collection" class="section collection">
            <div class="collection__container" data-aos="fade-up" data-aos-duration="1200">
                <img class="collection_02 collection__box" src="./assets/img/2.jpg" alt="MedTrack Collection 1" loading="lazy">
                <img class="collection_01 collection__box" src="./assets/img/5.jpg" alt="MedTrack Collection 2" loading="lazy">
            </div>
        </section>

        <!-- Products Section -->
        <section class="section latest__products" id="products">
            <div class="title__container">
                <div class="section__title active">
                    <span class="dot"></span>
                    <h1 class="primary__title">
                        <?php echo $search_performed ? 'Search Results for: ' . htmlspecialchars($search_query) : 'All Products'; ?>
                    </h1>
                </div>
            </div>

            <div class="container new" style="width: 100%;">
                <div class="facility__contianer" data-aos="fade-up" data-aos-duration="1200" style="margin-right:2vw !important;">
                    
                    <?php if (empty($products)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-search" style="font-size: 3rem; color: #ccc;"></i>
                            <h3 class="mt-3">No products found</h3>
                            <?php if ($search_performed): ?>
                                <p class="text-muted">Try searching with different keywords or browse all products</p>
                                <a href="index.php" class="btn btn-primary">View All Products</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="facility__box" style="margin: 10px; height: auto; width: 300px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                
                                <div class="product__header">
                                    <img src="uploaded_img/<?php echo htmlspecialchars($product['image_01']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px;">
                                </div>
                                
                                <div class="product__footer">
                                    <h3 style="color: #6f42c1; font-size: 1.2rem; margin: 10px 0;">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h3>
                                    
                                    <div style='display:flex; justify-content: space-between; padding-bottom:5px; align-items: center;'>
                                        <p style="color: #28a745; font-weight: bold; font-size: 1.1rem;">
                                            ₹<?php echo number_format($product['price'], 2); ?>
                                        </p>
                                        <span class="badge <?php echo $product['stock_quantity'] > 10 ? 'bg-success' : ($product['stock_quantity'] > 0 ? 'bg-warning' : 'bg-danger'); ?>">
                                            <?php echo $product['stock_quantity']; ?> in stock
                                        </span>
                                    </div>

                                    <div style='display:flex; justify-content: space-between; padding-bottom:5px; align-items: center;'>
                                        <h4 style="font-size: 1rem; color: #495057;">
                                            <?php echo htmlspecialchars($product['shop_name']); ?>
                                        </h4>
                                        <span class="badge bg-info">
                                            <?php echo htmlspecialchars($product['category_name']); ?>
                                        </span>
                                    </div>

                                    <div class="d-grid gap-2 mb-2">
                                        <a class="btn btn-primary" href="prod_desc.php?show=<?php echo $product['id']; ?>">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a data-tip="Quick View" data-place="left" href="prod_desc.php?show=<?php echo $product['id']; ?>" 
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a data-tip="Shop Location" data-place="left" href="map.php?shop=<?php echo $product['shop_id']; ?>" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-geo-alt"></i>
                                        </a>
                                        <a data-tip="Add to Cart" data-place="left" href="checkout.php?product_id=<?php echo $product['id']; ?>" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-cart-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
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
                            <svg>
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
                                    <img src="./assets/img/p1.jpg" alt="Vijay Gupta Profile" loading="lazy">
                                </div>
                                <p>MedTrack has revolutionized how we manage our pharmacy. The real-time stock updates and location-based search have significantly improved our customer service.</p>
                                <div class="client__info">
                                    <h3>Vijay Gupta</h3>
                                    <span>CEO at AllInOne Pharmacy</span>
                                </div>
                            </div>
                        </li>

                        <li class="glide__slide">
                            <div class="testimonial__box">
                                <div class="client__image">
                                    <img src="./assets/img/p4.jpg" alt="Neha Malhotra Profile" loading="lazy">
                                </div>
                                <p>The platform's ability to connect patients with rare medicines has been a game-changer. Our customers can now easily find specialized medications.</p>
                                <div class="client__info">
                                    <h3>Neha Malhotra</h3>
                                    <span>MD at Medico Solutions</span>
                                </div>
                            </div>
                        </li>
                        
                        <li class="glide__slide">
                            <div class="testimonial__box">
                                <div class="client__image">
                                    <img src="./assets/img/p3.jpg" alt="Jennifer Smith Profile" loading="lazy">
                                </div>
                                <p>As a global pharmaceutical company, we appreciate MedTrack's commitment to connecting patients with the medicines they need, when they need them.</p>
                                <div class="client__info">
                                    <h3>Jennifer Smith</h3>
                                    <span>MD & CEO at GlobalPharma</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="glide__bullets" data-glide-el="controls[nav]">
                    <button class="glide__bullet" data-glide-dir="=0" aria-label="Go to testimonial 1"></button>
                    <button class="glide__bullet" data-glide-dir="=1" aria-label="Go to testimonial 2"></button>
                    <button class="glide__bullet" data-glide-dir="=2" aria-label="Go to testimonial 3"></button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('./components/customer_footer.php'); ?>