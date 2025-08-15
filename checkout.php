<?php
$page_title = 'MedTrack - Buy Medicine';
include('./components/connect.php');
include('./components/customer_header.php');

// Validate and sanitize input
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header('Location: index.php');
    exit();
}

$product_id = (int)$_GET['product_id'];

// Use prepared statement to prevent SQL injection
$select_query = "SELECT p.*, pc.name as category_name, s.shop_name, s.address 
                 FROM products p 
                 INNER JOIN product_categories pc ON p.category_id = pc.id 
                 INNER JOIN shopkeeper_accounts s ON p.shop_id = s.id 
                 WHERE p.id = ? AND p.is_active = 1";

$stmt = $conn->prepare($select_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

// Check if product is in stock
if ($product['stock_quantity'] <= 0) {
    echo '<div class="alert alert-warning text-center mt-4">
            <h4>Product Out of Stock</h4>
            <p>Sorry, this product is currently out of stock.</p>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
          </div>';
    include('./components/customer_footer.php');
    exit();
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Razorpay -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<main style="margin-bottom:100px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Purchase <?php echo htmlspecialchars($product['name']); ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Product Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <img src="uploaded_img/<?php echo htmlspecialchars($product['image_01']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="text-muted"><?php echo htmlspecialchars($product['category_name']); ?></p>
                                <p class="text-muted">Shop: <?php echo htmlspecialchars($product['shop_name']); ?></p>
                                <h4 class="text-success">₹<?php echo number_format($product['price'], 2); ?></h4>
                                <span class="badge <?php echo $product['stock_quantity'] > 10 ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo $product['stock_quantity']; ?> in stock
                                </span>
                            </div>
                        </div>

                        <hr>

                        <!-- Customer Information Form -->
                        <form id="checkoutForm" class="needs-validation" novalidate>
                            <h5 class="mb-3">Customer Information</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="billing_name" class="form-label">
                                        <i class="fas fa-user me-2"></i>Full Name *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="billing_name" 
                                           name="billing_name" 
                                           placeholder="Enter your full name" 
                                           required 
                                           maxlength="100"
                                           pattern="[A-Za-z\s]+"
                                           title="Please enter only letters and spaces">
                                    <div class="invalid-feedback">
                                        Please enter your full name.
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="billing_email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Email Address *
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="billing_email" 
                                           name="billing_email" 
                                           placeholder="Enter your email" 
                                           required 
                                           maxlength="100">
                                    <div class="invalid-feedback">
                                        Please enter a valid email address.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="billing_mobile" class="form-label">
                                        <i class="fas fa-phone me-2"></i>Mobile Number *
                                    </label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="billing_mobile" 
                                           name="billing_mobile" 
                                           placeholder="Enter 10-digit mobile number" 
                                           required 
                                           pattern="[0-9]{10}"
                                           title="Please enter a 10-digit mobile number">
                                    <div class="invalid-feedback">
                                        Please enter a valid 10-digit mobile number.
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">
                                        <i class="fas fa-pills me-2"></i>Quantity *
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="quantity" 
                                           name="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="<?php echo $product['stock_quantity']; ?>" 
                                           required>
                                    <div class="invalid-feedback">
                                        Please enter a valid quantity.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="payAmount" class="form-label">
                                        <i class="fas fa-rupee-sign me-2"></i>Total Amount
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="payAmount" 
                                           name="payAmount" 
                                           value="<?php echo $product['price']; ?>" 
                                           readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-shield-alt me-2"></i>Payment Method
                                    </label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-success">Secure Payment via Razorpay</span>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="product_id" value="<?php echo $product_id; ?>">
                            
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" id="PayNow" class="btn btn-success btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Calculate total amount based on quantity
document.getElementById('quantity').addEventListener('change', function() {
    const quantity = parseInt(this.value);
    const unitPrice = <?php echo $product['price']; ?>;
    const total = quantity * unitPrice;
    document.getElementById('payAmount').value = total.toFixed(2);
});

// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    event.preventDefault();
                    processPayment();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Payment processing
function processPayment() {
    const formData = new FormData(document.getElementById('checkoutForm'));
    const data = {
        billing_name: formData.get('billing_name'),
        billing_mobile: formData.get('billing_mobile'),
        billing_email: formData.get('billing_email'),
        shipping_name: formData.get('billing_name'),
        shipping_mobile: formData.get('billing_mobile'),
        shipping_email: formData.get('billing_email'),
        quantity: formData.get('quantity'),
        paymentOption: "netbanking",
        payAmount: formData.get('payAmount'),
        product_id: formData.get('product_id'),
        action: 'payOrder'
    };

    // Show loading state
    const submitBtn = document.getElementById('PayNow');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    submitBtn.disabled = true;

    fetch('submit_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.res === 'success') {
            const orderID = data.order_number;
            const options = {
                "key": data.razorpay_key,
                "amount": data.userData.amount * 100, // Convert to paise
                "currency": "INR",
                "name": "MedTrack",
                "description": data.userData.description,
                "image": "./assets/img/trolley.png",
                "order_id": data.userData.rpay_order_id,
                "handler": function(response) {
                    window.location.replace("success.php?oid=" + orderID + 
                        "&rp_payment_id=" + response.razorpay_payment_id + 
                        "&rp_signature=" + response.razorpay_signature);
                },
                "modal": {
                    "ondismiss": function() {
                        window.location.replace("success.php?oid=" + orderID);
                    }
                },
                "prefill": {
                    "name": data.userData.name,
                    "email": data.userData.email,
                    "contact": data.userData.mobile
                },
                "notes": {
                    "address": "MedTrack"
                },
                "theme": {
                    "color": "#28a745"
                }
            };
            
            const rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function(response) {
                window.location.replace("failed.php?oid=" + orderID + 
                    "&reason=" + encodeURIComponent(response.error.description) + 
                    "&paymentid=" + response.error.metadata.payment_id);
            });
            rzp1.open();
        } else {
            alert('Payment initialization failed: ' + (data.info || 'Unknown error'));
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your payment. Please try again.');
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
</script>

<?php include('./components/customer_footer.php'); ?>