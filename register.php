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
            background: linear-gradient(135deg, var(--deep-obsidian) 0%, var(--primary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 40px 20px;
        }
        .register-card {
            max-width: 540px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .form-control:focus, .form-select:focus {
            background-color: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: var(--primary-color);
        }
        .btn-register {
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body>

<div class="register-card fade-in-up">
    <div class="mb-5 text-center">
        <h2 class="fw-bold text-dark mb-2">Create Account</h2>
        <p class="text-muted">Fill in your details to get started with JHMS.</p>
    </div>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4" role="alert">
            <i class="fas fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success_msg): ?>
        <div class="text-center py-5 glass rounded-4">
            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-4 text-success">
                <i class="fas fa-check-circle fa-4x"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">Registration Successful!</h3>
            <p class="text-muted mb-4">Welcome to the community. You can now access your dashboard.</p>
            <a href="login.php" class="btn btn-primary px-5 py-3 rounded-pill shadow">
                CONTINUE TO LOGIN <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    <?php else: ?>

    <form action="register.php" method="POST">
        <div class="row g-3">
            <div class="col-12 text-start">
                <label for="full_name" class="form-label small fw-bold text-muted">FULL NAME</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="E.g. Ali Ahmed" required>
            </div>
            
            <div class="col-md-7 text-start">
                <label for="email" class="form-label small fw-bold text-muted">EMAIL ADDRESS</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            </div>

            <div class="col-md-5 text-start">
                <label for="role" class="form-label small fw-bold text-muted">ACCOUNT TYPE</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                </select>
            </div>

            <div class="col-md-6 text-start">
                <label for="password" class="form-label small fw-bold text-muted">PASSWORD</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="col-md-6 text-start">
                <label for="confirm_password" class="form-label small fw-bold text-muted">CONFIRM</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-register mt-5 mb-4 shadow">CREATE MY ACCOUNT</button>
    </form>
    <?php endif; ?>
    
    <div class="text-center mt-4">
        <p class="mb-4 text-muted">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Sign in here</a></p>
        <a href="index.php" class="btn btn-light border-0 text-muted rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Back to Homepage
        </a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
