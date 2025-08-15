<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE');
header("Content-Type: application/json");
header("Accept: application/json");
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Access-Control-Allow-Methods, Content-Type');

// Include database connection
include('./components/connect.php');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['action']) && $input['action'] == 'payOrder') {
    
    // Validate required fields
    $required_fields = ['billing_name', 'billing_mobile', 'billing_email', 'payAmount', 'product_id'];
    foreach ($required_fields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            echo json_encode(['res' => 'error', 'info' => "Missing required field: $field"]);
            exit;
        }
    }
    
    // Sanitize and validate input
    $billing_name = trim($input['billing_name']);
    $billing_mobile = trim($input['billing_mobile']);
    $billing_email = trim($input['billing_email']);
    $shipping_name = trim($input['shipping_name'] ?? $billing_name);
    $shipping_mobile = trim($input['shipping_mobile'] ?? $billing_mobile);
    $shipping_email = trim($input['shipping_email'] ?? $billing_email);
    $quantity = (int)($input['quantity'] ?? 1);
    $paymentOption = trim($input['paymentOption'] ?? 'netbanking');
    $payAmount = (float)$input['payAmount'];
    $product_id = (int)$input['product_id'];
    
    // Validate email
    if (!filter_var($billing_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['res' => 'error', 'info' => 'Invalid email address']);
        exit;
    }
    
    // Validate mobile number
    if (!preg_match('/^[0-9]{10}$/', $billing_mobile)) {
        echo json_encode(['res' => 'error', 'info' => 'Invalid mobile number']);
        exit;
    }
    
    // Validate amount
    if ($payAmount <= 0) {
        echo json_encode(['res' => 'error', 'info' => 'Invalid amount']);
        exit;
    }
    
    // Validate product exists and has sufficient stock
    $product_query = "SELECT p.*, s.shop_name FROM products p 
                      INNER JOIN shopkeeper_accounts s ON p.shop_id = s.id 
                      WHERE p.id = ? AND p.is_active = 1";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
    
    if ($product_result->num_rows === 0) {
        echo json_encode(['res' => 'error', 'info' => 'Product not found']);
        exit;
    }
    
    $product = $product_result->fetch_assoc();
    
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode(['res' => 'error', 'info' => 'Insufficient stock']);
        exit;
    }
    
    $stmt->close();
    
    // Razorpay configuration (use environment variables in production)
    $razorpay_mode = 'test'; // Change to 'live' for production
    
    if ($razorpay_mode == 'test') {
        $razorpay_key = 'rzp_test_pi2fEEfhC66GKs'; // Your Test Key
        $razorpay_secret_key = 'jzWG8EKZkK9JEQMqjlCaWG7W'; // Your Test Secret Key
    } else {
        $razorpay_key = getenv('RAZORPAY_LIVE_KEY'); // Use environment variables
        $razorpay_secret_key = getenv('RAZORPAY_LIVE_SECRET');
    }
    
    // Generate unique order ID
    $order_id = 'MT' . date('YmdHis') . rand(1000, 9999);
    
    // Prepare Razorpay order request
    $postdata = array(
        "amount" => (int)($payAmount * 100), // Convert to paise
        "currency" => "INR",
        "receipt" => $order_id,
        "notes" => array(
            "product_id" => $product_id,
            "quantity" => $quantity,
            "shop_name" => $product['shop_name']
        )
    );
    
    // Create Razorpay order
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($postdata),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($razorpay_key . ":" . $razorpay_secret_key)
        ),
    ));
    
    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($http_code !== 200) {
        echo json_encode(['res' => 'error', 'info' => 'Failed to create Razorpay order']);
        exit;
    }
    
    $orderRes = json_decode($response);
    
    if (!isset($orderRes->id)) {
        echo json_encode(['res' => 'error', 'info' => 'Invalid response from Razorpay']);
        exit;
    }
    
    $rpay_order_id = $orderRes->id;
    
    // Insert order into database
    $insert_order = "INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, 
                                        product_id, quantity, total_amount, payment_status, 
                                        razorpay_order_id, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?, NOW())";
    
    $stmt = $conn->prepare($insert_order);
    $stmt->bind_param("sssiiids", $order_id, $billing_name, $billing_email, $billing_mobile, 
                      $product_id, $quantity, $payAmount, $rpay_order_id);
    
    if ($stmt->execute()) {
        // Update product stock
        $update_stock = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
        $stock_stmt = $conn->prepare($update_stock);
        $stock_stmt->bind_param("ii", $quantity, $product_id);
        $stock_stmt->execute();
        $stock_stmt->close();
        
        // Prepare response data
        $dataArr = array(
            'amount' => $payAmount,
            'description' => "Purchase of " . $product['name'] . " (Qty: $quantity) - ₹" . number_format($payAmount, 2),
            'rpay_order_id' => $rpay_order_id,
            'name' => $billing_name,
            'email' => $billing_email,
            'mobile' => $billing_mobile
        );
        
        echo json_encode([
            'res' => 'success', 
            'order_number' => $order_id, 
            'userData' => $dataArr, 
            'razorpay_key' => $razorpay_key
        ]);
        
        $stmt->close();
    } else {
        echo json_encode(['res' => 'error', 'info' => 'Database error: ' . $stmt->error]);
        $stmt->close();
    }
    
} else {
    echo json_encode(['res' => 'error', 'info' => 'Invalid request']);
}
?>
