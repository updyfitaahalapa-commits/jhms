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

<div class="container-fluid py-4">
    <div class="row g-4 mb-5 fade-in-up">
        <!-- Dashboard Header -->
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">System Administration</h2>
            <p class="text-muted">Global platform overview and management controls.</p>
        </div>

        <!-- Total Users Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-index-1">
                        <h6 class="text-white text-uppercase ls-wide opacity-75 mb-3 small fw-bold">Verified Users</h6>
                        <h2 class="display-5 fw-bold text-white mb-2"><?php echo $total_users; ?></h2>
                        <div class="text-white-50 small d-flex gap-2">
                             <span class="badge bg-white bg-opacity-10 rounded-pill">Employers: <?php echo $employers; ?></span>
                             <span class="badge bg-white bg-opacity-10 rounded-pill">Seekers: <?php echo $seekers; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Jobs Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-index-1">
                        <h6 class="text-white text-uppercase ls-wide opacity-75 mb-3 small fw-bold">Live Opportunities</h6>
                        <h2 class="display-5 fw-bold text-white mb-0"><?php echo $total_jobs; ?></h2>
                    </div>
                    <i class="fas fa-briefcase position-absolute end-0 bottom-0 opacity-10 translate-middle-y me-4" style="font-size: 5rem; color: #fff;"></i>
                </div>
            </div>
        </div>

        <!-- Total Applications Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-index-1">
                        <h6 class="text-white text-uppercase ls-wide opacity-75 mb-3 small fw-bold">Submissions</h6>
                        <h2 class="display-5 fw-bold text-white mb-0"><?php echo $total_apps; ?></h2>
                    </div>
                    <i class="fas fa-file-invoice position-absolute end-0 bottom-0 opacity-10 translate-middle-y me-4" style="font-size: 5rem; color: #fff;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row fade-in-up" style="animation-delay: 0.2s">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-4 border-0">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-screwdriver-wrench me-2 text-accent"></i>Management Overlays</h5>
                </div>
                <div class="card-body pt-0 pb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="manage_users.php" class="btn btn-outline-secondary border-2 w-100 py-3 rounded-4 hvr-grow">
                                <i class="fas fa-users-gear me-2"></i> Manage All Users
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="manage_jobs.php" class="btn btn-outline-secondary border-2 w-100 py-3 rounded-4 hvr-grow">
                                <i class="fas fa-briefcase me-2"></i> Audit Job Listings
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="manage_applications.php" class="btn btn-outline-secondary border-2 w-100 py-3 rounded-4 hvr-grow">
                                <i class="fas fa-file-invoice me-2"></i> Data Reporting
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
