<?php
// seeker/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seeker') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard - JHMS</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        #sidebar .sidebar-header h3 i {
            color: var(--accent-color);
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-user-tie me-2"></i>JHMS</h3>
            <p class="text-white-50 small mb-0">Seeker Panel</p>
        </div>

        <ul class="list-unstyled components">
            <li class="mb-2">
                <a href="../index.php"><i class="fas fa-home"></i> Home Site</a>
            </li>
             <li class="mb-2 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="fas fa-grid-2"></i> Dashboard</a>
            </li>
            <li class="mb-2 <?php echo basename($_SERVER['PHP_SELF']) == 'browse_jobs.php' ? 'active' : ''; ?>">
                <a href="browse_jobs.php"><i class="fas fa-briefcase"></i> Browse Jobs</a>
            </li>
            <li class="mb-2 <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
            </li>
            <li class="mt-4 pt-4 border-top border-secondary border-opacity-10">
                <a href="../logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg border-0 shadow-none mb-4">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary border-0 btn-sm">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                
                <div class="d-flex align-items-center ms-auto">
                    <div class="d-none d-md-block text-end me-3">
                        <small class="text-muted d-block">Logged in as Seeker</small>
                        <span class="fw-bold text-dark"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                    </div>
                    <a href="profile.php" class="p-0 border-0">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name']); ?>&background=6366F1&color=fff&rounded=true" width="40" height="40" alt="Avatar">
                    </a>
                </div>
            </div>
        </nav>

