<?php
// employer/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - JHMS</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-briefcase me-2"></i>JHMS</h3>
            <p class="text-white-50 small mb-0">Employer Panel</p>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="../index.php"><i class="fas fa-home me-2"></i> Home Site</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'post_job.php' ? 'active' : ''; ?>">
                <a href="post_job.php"><i class="fas fa-plus-circle"></i> Post a Job</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_jobs.php' ? 'active' : ''; ?>">
                <a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_applicants.php' ? 'active' : ''; ?>">
                <a href="view_applicants.php"><i class="fas fa-users"></i> Manage Applicants</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php"><i class="fas fa-building"></i> Company Profile</a>
            </li>
            <li>
                <a href="../logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                    <span class="me-3 text-secondary d-none d-md-block">Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong></span>
                    <a href="profile.php" class="btn btn-outline-primary btn-sm rounded-circle"><i class="fas fa-user"></i></a>
                </div>
            </div>
        </nav>

