<?php
/**
 * MedTrack Setup Script
 * This script helps configure the application for first-time use
 */

session_start();

// Check if already configured
if (file_exists('config/config.php')) {
    header('Location: index.php');
    exit();
}

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];
$success = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1: // Database configuration
            $db_host = trim($_POST['db_host']);
            $db_username = trim($_POST['db_username']);
            $db_password = $_POST['db_password'];
            $db_name = trim($_POST['db_name']);
            
            // Test database connection
            try {
                $conn = new mysqli($db_host, $db_username, $db_password);
                if ($conn->connect_error) {
                    $errors[] = "Database connection failed: " . $conn->connect_error;
                } else {
                    // Create database if it doesn't exist
                    if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`")) {
                        $errors[] = "Failed to create database: " . $conn->error;
                    } else {
                        $conn->select_db($db_name);
                        
                        // Import database schema
                        $sql_file = 'medtrack_db.sql';
                        if (file_exists($sql_file)) {
                            $sql = file_get_contents($sql_file);
                            $queries = explode(';', $sql);
                            
                            foreach ($queries as $query) {
                                $query = trim($query);
                                if (!empty($query)) {
                                    if (!$conn->query($query)) {
                                        $errors[] = "SQL Error: " . $conn->error;
                                        break;
                                    }
                                }
                            }
                            
                            if (empty($errors)) {
                                $_SESSION['db_config'] = [
                                    'host' => $db_host,
                                    'username' => $db_username,
                                    'password' => $db_password,
                                    'name' => $db_name
                                ];
                                $step = 2;
                            }
                        } else {
                            $errors[] = "Database schema file not found: $sql_file";
                        }
                    }
                }
                $conn->close();
            } catch (Exception $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
            break;
            
        case 2: // Application configuration
            $app_name = trim($_POST['app_name']);
            $app_url = trim($_POST['app_url']);
            $timezone = trim($_POST['timezone']);
            
            if (empty($app_name) || empty($app_url)) {
                $errors[] = "Please fill in all required fields";
            } else {
                $_SESSION['app_config'] = [
                    'name' => $app_name,
                    'url' => $app_url,
                    'timezone' => $timezone
                ];
                $step = 3;
            }
            break;
            
        case 3: // Admin account creation
            $admin_username = trim($_POST['admin_username']);
            $admin_email = trim($_POST['admin_email']);
            $admin_password = $_POST['admin_password'];
            $admin_confirm_password = $_POST['admin_confirm_password'];
            
            if (empty($admin_username) || empty($admin_email) || empty($admin_password)) {
                $errors[] = "Please fill in all required fields";
            } elseif ($admin_password !== $admin_confirm_password) {
                $errors[] = "Passwords do not match";
            } elseif (strlen($admin_password) < 8) {
                $errors[] = "Password must be at least 8 characters long";
            } elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email address";
            } else {
                $_SESSION['admin_config'] = [
                    'username' => $admin_username,
                    'email' => $admin_email,
                    'password' => $admin_password
                ];
                $step = 4;
            }
            break;
            
        case 4: // Final configuration
            try {
                // Create config directory
                if (!is_dir('config')) {
                    mkdir('config', 0755, true);
                }
                
                // Create logs directory
                if (!is_dir('logs')) {
                    mkdir('logs', 0755, true);
                }
                
                // Generate configuration file
                $config_content = generateConfigFile();
                if (file_put_contents('config/config.php', $config_content) === false) {
                    throw new Exception("Failed to create configuration file");
                }
                
                // Create admin account
                $conn = new mysqli(
                    $_SESSION['db_config']['host'],
                    $_SESSION['db_config']['username'],
                    $_SESSION['db_config']['password'],
                    $_SESSION['db_config']['name']
                );
                
                $admin_password_hash = password_hash($_SESSION['admin_config']['password'], PASSWORD_DEFAULT);
                $insert_admin = "INSERT INTO admin_accounts (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, 'super_admin')";
                $stmt = $conn->prepare($insert_admin);
                $stmt->bind_param("sss", 
                    $_SESSION['admin_config']['username'],
                    $_SESSION['admin_config']['email'],
                    $admin_password_hash,
                    $_SESSION['admin_config']['username']
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to create admin account: " . $stmt->error);
                }
                
                $stmt->close();
                $conn->close();
                
                // Clear session data
                unset($_SESSION['db_config']);
                unset($_SESSION['app_config']);
                unset($_SESSION['admin_config']);
                
                $success[] = "Setup completed successfully!";
                $step = 5;
                
            } catch (Exception $e) {
                $errors[] = "Setup failed: " . $e->getMessage();
            }
            break;
    }
}

function generateConfigFile() {
    $db_config = $_SESSION['db_config'];
    $app_config = $_SESSION['app_config'];
    
    return "<?php
/**
 * MedTrack Configuration File
 * Generated automatically by setup script
 */

// Database Configuration
define('DB_HOST', '{$db_config['host']}');
define('DB_USERNAME', '{$db_config['username']}');
define('DB_PASSWORD', '{$db_config['password']}');
define('DB_NAME', '{$db_config['name']}');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', '{$app_config['name']}');
define('APP_VERSION', '2.0.0');
define('APP_URL', '{$app_config['url']}');
define('APP_TIMEZONE', '{$app_config['timezone']}');

// Security Configuration
define('SESSION_TIMEOUT', 3600);
define('CSRF_TOKEN_EXPIRY', 1800);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900);

// Password Configuration
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_REQUIRE_SPECIAL', true);
define('PASSWORD_REQUIRE_NUMBERS', true);
define('PASSWORD_REQUIRE_UPPERCASE', true);

// File Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_PATH', 'uploaded_img/');

// Razorpay Configuration
define('RAZORPAY_MODE', 'test');
define('RAZORPAY_TEST_KEY', 'your_test_key_here');
define('RAZORPAY_TEST_SECRET', 'your_test_secret_here');
define('RAZORPAY_LIVE_KEY', '');
define('RAZORPAY_LIVE_SECRET', '');

// Error Reporting
if (\$_SERVER['SERVER_NAME'] === 'localhost' || \$_SERVER['SERVER_NAME'] === '127.0.0.1') {
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
}

// CSRF Protection
function generateCSRFToken() {
    if (!isset(\$_SESSION['csrf_token']) || !isset(\$_SESSION['csrf_token_expiry']) || 
        time() > \$_SESSION['csrf_token_expiry']) {
        \$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        \$_SESSION['csrf_token_expiry'] = time() + CSRF_TOKEN_EXPIRY;
    }
    return \$_SESSION['csrf_token'];
}

function validateCSRFToken(\$token) {
    return isset(\$_SESSION['csrf_token']) && 
           isset(\$_SESSION['csrf_token_expiry']) && 
           time() <= \$_SESSION['csrf_token_expiry'] && 
           hash_equals(\$_SESSION['csrf_token'], \$token);
}

// Input Sanitization
function sanitizeInput(\$input) {
    if (is_array(\$input)) {
        return array_map('sanitizeInput', \$input);
    }
    return htmlspecialchars(trim(\$input), ENT_QUOTES, 'UTF-8');
}

// Set security headers
setSecurityHeaders();
?>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedTrack Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 50px auto;
        }
        .step-indicator {
            margin-bottom: 30px;
        }
        .step {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            margin: 0 10px;
            font-weight: bold;
        }
        .step.active {
            background-color: #007bff;
            color: white;
        }
        .step.completed {
            background-color: #28a745;
            color: white;
        }
        .step.pending {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="setup-container">
        <div class="text-center mb-4">
            <img src="assets/img/trolley.png" alt="MedTrack" style="height: 80px; margin-bottom: 20px;">
            <h1>MedTrack Setup</h1>
            <p class="text-muted">Complete the setup to get started with your pharmacy management system</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator text-center">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="step <?php echo $i < $step ? 'completed' : ($i == $step ? 'active' : 'pending'); ?>">
                    <?php echo $i; ?>
                </span>
            <?php endfor; ?>
        </div>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Success Messages -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <ul class="mb-0">
                    <?php foreach ($success as $msg): ?>
                        <li><?php echo htmlspecialchars($msg); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Setup Forms -->
        <div class="card shadow">
            <div class="card-body">
                <?php if ($step === 1): ?>
                    <!-- Database Configuration -->
                    <h4 class="mb-3">Step 1: Database Configuration</h4>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="db_host" class="form-label">Database Host</label>
                                <input type="text" class="form-control" id="db_host" name="db_host" 
                                       value="localhost" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="db_name" class="form-label">Database Name</label>
                                <input type="text" class="form-control" id="db_name" name="db_name" 
                                       value="medtrack_db" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="db_username" class="form-label">Database Username</label>
                                <input type="text" class="form-control" id="db_username" name="db_username" 
                                       value="root" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="db_password" class="form-label">Database Password</label>
                                <input type="password" class="form-control" id="db_password" name="db_password">
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Test Connection & Continue</button>
                        </div>
                    </form>

                <?php elseif ($step === 2): ?>
                    <!-- Application Configuration -->
                    <h4 class="mb-3">Step 2: Application Configuration</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="app_name" class="form-label">Application Name</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" 
                                   value="MedTrack" required>
                        </div>
                        <div class="mb-3">
                            <label for="app_url" class="form-label">Application URL</label>
                            <input type="url" class="form-control" id="app_url" name="app_url" 
                                   value="http://localhost/MedTrack" required>
                        </div>
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Timezone</label>
                            <select class="form-select" id="timezone" name="timezone" required>
                                <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">America/New_York (EST)</option>
                                <option value="Europe/London">Europe/London (GMT)</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </div>
                    </form>

                <?php elseif ($step === 3): ?>
                    <!-- Admin Account Creation -->
                    <h4 class="mb-3">Step 3: Create Admin Account</h4>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_username" class="form-label">Admin Username</label>
                                <input type="text" class="form-control" id="admin_username" name="admin_username" 
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_password" class="form-label">Admin Password</label>
                                <input type="password" class="form-control" id="admin_password" name="admin_password" 
                                       minlength="8" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="admin_confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="admin_confirm_password" 
                                       name="admin_confirm_password" minlength="8" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </div>
                    </form>

                <?php elseif ($step === 4): ?>
                    <!-- Final Configuration -->
                    <h4 class="mb-3">Step 4: Final Configuration</h4>
                    <p>Please wait while we complete the setup...</p>
                    <form method="POST">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Complete Setup</button>
                        </div>
                    </form>

                <?php elseif ($step === 5): ?>
                    <!-- Setup Complete -->
                    <div class="text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Setup Completed Successfully!</h4>
                        <p class="text-muted">Your MedTrack pharmacy management system is now ready to use.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-shield text-primary mb-2" style="font-size: 2rem;"></i>
                                        <h6>Admin Panel</h6>
                                        <a href="admin/login.php" class="btn btn-outline-primary btn-sm">Access</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-store text-success mb-2" style="font-size: 2rem;"></i>
                                        <h6>Shopkeeper Panel</h6>
                                        <a href="shopkeeper/login.php" class="btn btn-outline-success btn-sm">Access</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-home text-info mb-2" style="font-size: 2rem;"></i>
                                        <h6>Homepage</h6>
                                        <a href="index.php" class="btn btn-outline-info btn-sm">Access</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h6>Default Login Credentials:</h6>
                            <p class="text-muted">
                                <strong>Admin:</strong> Use the credentials you just created<br>
                                <strong>Shopkeeper:</strong> john@medtrack.com / admin123
                            </p>
                        </div>
                        
                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary btn-lg">Go to Homepage</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navigation -->
        <?php if ($step > 1 && $step < 5): ?>
            <div class="text-center mt-3">
                <a href="?step=<?php echo $step - 1; ?>" class="btn btn-outline-secondary">Previous</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
