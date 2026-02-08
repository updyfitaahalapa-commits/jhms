<?php
// register.php
session_start();
require_once 'includes/db_connect.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Basic Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error_msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Passwords do not match.";
    } elseif (!in_array($role, ['employer', 'seeker'])) {
        $error_msg = "Invalid role selected.";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error_msg = "Email is already registered.";
            } else {
                // Hash the password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $insert_sql = "INSERT INTO users (full_name, email, password_hash, role) VALUES (:full_name, :email, :password_hash, :role)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bindParam(':full_name', $full_name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
                $stmt->bindParam(':role', $role, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $success_msg = "Registration successful! You can now <a href='login.php'>Login here</a>.";
                } else {
                    $error_msg = "Registration failed. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $error_msg = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Hunting System</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, var(--primary-color) 0%, #004085 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            max-width: 500px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            background: white;
        }
        .register-header {
            background-color: white;
            padding: 30px 20px 10px;
            text-align: center;
        }
        .register-body {
            padding: 20px 40px 40px;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-header">
        <h3 class="fw-bold text-primary mb-1">Create Account</h3>
        <p class="text-muted small">Join us to find your dream job or ideal cnadidate</p>
    </div>
    <div class="register-body">
        
        <?php if ($error_msg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i> <?php echo htmlspecialchars($error_msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success_msg): ?>
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                <?php echo $success_msg; ?>
            </div>
        <?php else: ?>

        <form action="register.php" method="POST">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" required>
                <label for="full_name">Full Name</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email Address</label>
            </div>
            
            <div class="form-floating mb-3">
                <select class="form-select" id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                </select>
                <label for="role">I am a...</label>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                        <label for="confirm_password">Confirm Password</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">Register</button>
        </form>
        <?php endif; ?>
        
        <div class="text-center">
            <p class="mb-2 text-muted small">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Login here</a></p>
            <a href="index.php" class="text-secondary small text-decoration-none"><i class="fas fa-arrow-left me-1"></i> Back to Home</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
