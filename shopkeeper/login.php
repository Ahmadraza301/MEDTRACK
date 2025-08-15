<?php
session_start();

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid request!';
    } else {
        include('../components/connect.php');
        
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        // Input validation
        if (empty($email) || empty($password)) {
            $message = 'Please fill in all fields!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address!';
        } else {
            // Use prepared statements to prevent SQL injection
            $select_shopkeeper = "SELECT * FROM shopkeeper_accounts WHERE email = ? AND is_active = 1";
            $stmt = $conn->prepare($select_shopkeeper);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $shopkeeper = $result->fetch_assoc();
                
                // Verify password using password_verify
                if (password_verify($password, $shopkeeper['password_hash'])) {
                    $_SESSION['shopkeeper_id'] = $shopkeeper['id'];
                    $_SESSION['shopkeeper_name'] = $shopkeeper['shopkeeper_name'];
                    $_SESSION['shop_name'] = $shopkeeper['shop_name'];
                    $_SESSION['shopkeeper_email'] = $shopkeeper['email'];
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    header('Location: home.php');
                    exit();
                } else {
                    $message = 'Incorrect email or password!';
                }
            } else {
                $message = 'Incorrect email or password!';
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Shopkeeper Login - MedTrack</title>
    <meta content="Shopkeeper login for MedTrack pharmacy management system" name="description">
    
    <!-- Favicons -->
    <link href="../assets/img/trolley.png" rel="icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/shopkeeper_style.css" rel="stylesheet">
    <link href="../assets/css/login-signup.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="form-container">
                    <div class="text-center mb-4">
                        <img src="../assets/img/trolley.png" alt="MedTrack" style="height: 60px; margin-bottom: 20px;">
                        <h2 class="text-center">Shopkeeper Login</h2>
                        <p class="text-muted">Access Your Pharmacy Dashboard</p>
                    </div>
                    
                    <?php if (isset($message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope-fill"></i>
                                </span>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Enter your email" 
                                       required 
                                       maxlength="100"
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password" 
                                       required 
                                       maxlength="255">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>
                        
                        <div class="text-center mb-3">
                            <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                        </div>
                        
                        <div class="text-center mb-3">
                            <p class="mb-1">Not yet registered? <a href="register.php" class="text-decoration-none">Register now!</a></p>
                            <p class="mb-0">Not a shopkeeper? <a href="../admin/login.php" class="text-decoration-none">Admin Login</a></p>
                        </div>
                        
                        <div class="text-center">
                            <a href="../index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-house-door me-2"></i>Back to Home
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
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
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>