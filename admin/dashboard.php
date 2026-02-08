<?php
// admin/dashboard.php
require_once '../includes/db_connect.php';
require_once 'header.php';

// Get Total Users
$stmt = $conn->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$total_users = array_sum($users);
$employers = isset($users['employer']) ? $users['employer'] : 0;
$seekers = isset($users['seeker']) ? $users['seeker'] : 0;

// Get Total Jobs
$stmt = $conn->prepare("SELECT COUNT(*) as total_jobs FROM jobs");
$stmt->execute();
$total_jobs = $stmt->fetch()['total_jobs'];

// Get Total Applications
$stmt = $conn->prepare("SELECT COUNT(*) as total_apps FROM applications");
$stmt->execute();
$total_apps = $stmt->fetch()['total_apps'];
?>

<div class="container-fluid">
    <div class="row g-4 mb-4">
        <!-- Total Users Card -->
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-white-50 text-uppercase mb-0">Total Users</h6>
                        <i class="fas fa-users fa-2x text-white-50"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $total_users; ?></h2>
                    <div class="mt-2 text-white-50 small">
                        <span>Employers: <?php echo $employers; ?></span> <span class="mx-1">|</span> <span>Seekers: <?php echo $seekers; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Jobs Card -->
        <div class="col-md-3">
            <div class="card bg-success text-white h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-white-50 text-uppercase mb-0">Active Jobs</h6>
                        <i class="fas fa-briefcase fa-2x text-white-50"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $total_jobs; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Applications Card -->
        <div class="col-md-3">
            <div class="card bg-info text-white h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-white-50 text-uppercase mb-0">Applications</h6>
                        <i class="fas fa-file-alt fa-2x text-white-50"></i>
                    </div>
                    <h2 class="display-4 fw-bold mb-0"><?php echo $total_apps; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-bolt me-2"></i>Quick Admin Actions</h5>
                </div>
                <div class="card-body">
                    <a href="manage_users.php" class="btn btn-primary btn-lg me-3 mb-2 shadow-sm">
                        <i class="fas fa-users-cog me-2"></i> Manage Users
                    </a>
                    <a href="manage_jobs.php" class="btn btn-success btn-lg me-3 mb-2 shadow-sm">
                        <i class="fas fa-briefcase me-2"></i> Manage Jobs
                    </a>
                    <a href="manage_applications.php" class="btn btn-info btn-lg text-white mb-2 shadow-sm">
                        <i class="fas fa-file-alt me-2"></i> View Applications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
