<?php
/**
 * MedTrack Configuration File
 * Centralized configuration for security, database, and application settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'medtrack_db');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'MedTrack');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/MedTrack');
define('APP_TIMEZONE', 'Asia/Kolkata');

// Security Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('CSRF_TOKEN_EXPIRY', 1800); // 30 minutes
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Password Configuration
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_REQUIRE_SPECIAL', true);
define('PASSWORD_REQUIRE_NUMBERS', true);
define('PASSWORD_REQUIRE_UPPERCASE', true);

// File Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_PATH', 'uploaded_img/');

// Razorpay Configuration
define('RAZORPAY_MODE', 'test'); // Change to 'live' for production
define('RAZORPAY_TEST_KEY', 'rzp_test_pi2fEEfhC66GKs');
define('RAZORPAY_TEST_SECRET', 'jzWG8EKZkK9JEQMqjlCaWG7W');
define('RAZORPAY_LIVE_KEY', getenv('RAZORPAY_LIVE_KEY') ?: '');
define('RAZORPAY_LIVE_SECRET', getenv('RAZORPAY_LIVE_SECRET') ?: '');

// Email Configuration (for future use)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_ENCRYPTION', 'tls');

// Error Reporting (disable in production)
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Security Headers
function setSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://checkout.razorpay.com; style-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src \'self\' data: https:; font-src \'self\' https://fonts.gstatic.com https://cdnjs.cloudflare.com;');
}

// CSRF Protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_expiry']) || 
        time() > $_SESSION['csrf_token_expiry']) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_expiry'] = time() + CSRF_TOKEN_EXPIRY;
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           isset($_SESSION['csrf_token_expiry']) && 
           time() <= $_SESSION['csrf_token_expiry'] && 
           hash_equals($_SESSION['csrf_token'], $token);
}

// Input Sanitization
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Password Validation
function validatePassword($password) {
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return false;
    }
    
    if (PASSWORD_REQUIRE_SPECIAL && !preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        return false;
    }
    
    if (PASSWORD_REQUIRE_NUMBERS && !preg_match('/[0-9]/', $password)) {
        return false;
    }
    
    if (PASSWORD_REQUIRE_UPPERCASE && !preg_match('/[A-Z]/', $password)) {
        return false;
    }
    
    return true;
}

// Rate Limiting
function checkRateLimit($key, $max_attempts = MAX_LOGIN_ATTEMPTS, $lockout_time = LOGIN_LOCKOUT_TIME) {
    $current_time = time();
    
    if (!isset($_SESSION['rate_limit'][$key])) {
        $_SESSION['rate_limit'][$key] = ['attempts' => 0, 'first_attempt' => $current_time];
    }
    
    $rate_limit = &$_SESSION['rate_limit'][$key];
    
    // Reset if lockout time has passed
    if ($current_time - $rate_limit['first_attempt'] > $lockout_time) {
        $rate_limit = ['attempts' => 0, 'first_attempt' => $current_time];
    }
    
    // Check if limit exceeded
    if ($rate_limit['attempts'] >= $max_attempts) {
        return false;
    }
    
    $rate_limit['attempts']++;
    return true;
}

// Logging
function logActivity($action, $details = '', $user_id = null) {
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action,
        'details' => $details,
        'user_id' => $user_id,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    // In production, you would log to a file or database
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        error_log(json_encode($log_entry) . "\n", 3, 'logs/activity.log');
    }
}

// Set security headers
setSecurityHeaders();
?>
