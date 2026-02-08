<?php
// admin/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JHMS</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-cogs me-2"></i>JHMS</h3>
            <p class="text-white-50 small mb-0">Admin Panel</p>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="../index.php"><i class="fas fa-home me-2"></i> Home Site</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
                <a href="manage_users.php"><i class="fas fa-users-cog me-2"></i> Manage Users</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_jobs.php' ? 'active' : ''; ?>">
                <a href="manage_jobs.php"><i class="fas fa-briefcase me-2"></i> Manage Jobs</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_applications.php' ? 'active' : ''; ?>">
                <a href="manage_applications.php"><i class="fas fa-file-alt me-2"></i> Applications</a>
            </li>
            <li>
                <a href="../logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-primary">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="d-flex align-items-center ms-auto">
                    <span class="me-3 text-secondary d-none d-md-block">Admin: <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </nav>

