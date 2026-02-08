<?php
// login.php
session_start();
require_once 'includes/db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        try {
            // Prepare SQL to prevent SQL injection using PDO
            $stmt = $conn->prepare("SELECT user_id, full_name, password_hash, role FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            // Check if user exists
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch();
                
                // Verify password
                if (password_verify($password, $user['password_hash'])) {
                    // Password is correct, start session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    // Role-based redirection
                    switch ($user['role']) {
                        case 'admin':
                            header("Location: admin/dashboard.php");
                            break;
                        case 'employer':
                            header("Location: employer/dashboard.php");
                            break;
                        case 'seeker':
                            header("Location: seeker/dashboard.php");
                            break;
                        default:
                            header("Location: index.php");
                    }
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "No account found with that email.";
            }
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Job Hunting System</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, var(--deep-obsidian) 0%, var(--primary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }
        .login-card {
            max-width: 440px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .form-control:focus {
            background-color: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: var(--primary-color);
        }
        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body>

<div class="login-card fade-in-up">
    <div class="mb-5 text-center">
        <h2 class="fw-bold text-dark mb-2">Welcome Back</h2>
        <p class="text-muted">Enter your credentials to access your dashboard.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4" role="alert">
            <i class="fas fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label small fw-bold text-muted">EMAIL ADDRESS</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
        </div>
        
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="password" class="form-label small fw-bold text-muted mb-0">PASSWORD</label>
                <a href="#" class="text-primary small fw-bold text-decoration-none">Forgot?</a>
            </div>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100 btn-login mb-4 shadow">SIGN IN TO JHMS</button>
    </form>
    
    <div class="text-center">
        <p class="mb-4 text-muted">Don't have an account yet? <a href="register.php" class="text-primary fw-bold text-decoration-none">Create a free account</a></p>
        <a href="index.php" class="btn btn-light border-0 text-muted rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Back to Homepage
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
