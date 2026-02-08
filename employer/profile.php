<?php
// employer/profile.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$employer_id = $_SESSION['user_id'];
$message = '';
$message_pwd = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT full_name, email, password_hash FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $employer_id);
$stmt->execute();
$user = $stmt->fetch();

// Update Info Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_info'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    if (empty($full_name) || empty($email)) {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger">Invalid email format.</div>';
    } else {
        // Check if email exists (excluding current user)
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = :email AND user_id != :user_id");
        $check->bindParam(':email', $email);
        $check->bindParam(':user_id', $employer_id);
        $check->execute();

        if ($check->rowCount() > 0) {
            $message = '<div class="alert alert-danger">Email already exists.</div>';
        } else {
            // Update
            $update = $conn->prepare("UPDATE users SET full_name = :full_name, email = :email WHERE user_id = :user_id");
            $update->bindParam(':full_name', $full_name);
            $update->bindParam(':email', $email);
            $update->bindParam(':user_id', $employer_id);

            if ($update->execute()) {
                $_SESSION['full_name'] = $full_name; // Update session
                $user['full_name'] = $full_name;
                $user['email'] = $email;
                $message = '<div class="alert alert-success">Profile updated successfully.</div>';
            } else {
                $message = '<div class="alert alert-danger">Error updating profile.</div>';
            }
        }
    }
}

// Change Password Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message_pwd = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif (!password_verify($current_password, $user['password_hash'])) {
        $message_pwd = '<div class="alert alert-danger">Incorrect current password.</div>';
    } elseif ($new_password !== $confirm_password) {
        $message_pwd = '<div class="alert alert-danger">New passwords do not match.</div>';
    } elseif (strlen($new_password) < 6) {
        $message_pwd = '<div class="alert alert-danger">Password must be at least 6 characters.</div>';
    } else {
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_pwd = $conn->prepare("UPDATE users SET password_hash = :password WHERE user_id = :user_id");
        $update_pwd->bindParam(':password', $new_hash);
        $update_pwd->bindParam(':user_id', $employer_id);

        if ($update_pwd->execute()) {
            $message_pwd = '<div class="alert alert-success">Password changed successfully.</div>';
            // Refresh user data (though password hash isn't displayed)
            $user['password_hash'] = $new_hash;
        } else {
            $message_pwd = '<div class="alert alert-danger">Error changing password.</div>';
        }
    }
}
?>

<div class="container-fluid">
    <div class="row g-4">
        <!-- Update Info -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>Update Information</h5>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <form action="profile.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" placeholder="Full Name" required>
                            <label for="full_name">Full Name</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
                            <label for="email">Email Address</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="update_info" class="btn btn-primary btn-lg">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-lock me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    <?php echo $message_pwd; ?>
                    <form action="profile.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" required>
                            <label for="current_password">Current Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
                            <label for="new_password">New Password</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                            <label for="confirm_password">Confirm New Password</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="change_password" class="btn btn-warning btn-lg text-white">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
