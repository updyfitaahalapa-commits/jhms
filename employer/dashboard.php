<?php
// employer/dashboard.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$employer_id = $_SESSION['user_id'];

// Get Total Jobs Posted
$stmt = $conn->prepare("SELECT COUNT(*) as total_jobs FROM jobs WHERE employer_id = :employer_id");
$stmt->bindParam(':employer_id', $employer_id, PDO::PARAM_INT);
$stmt->execute();
$total_jobs = $stmt->fetch()['total_jobs'];

// Get Total Applications Received for Employer's Jobs
$stmt = $conn->prepare("
    SELECT COUNT(*) as total_apps 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.job_id 
    WHERE j.employer_id = :employer_id
");
$stmt->bindParam(':employer_id', $employer_id, PDO::PARAM_INT);
$stmt->execute();
$total_apps = $stmt->fetch()['total_apps'];
?>

<div class="container-fluid py-4">
    <div class="row g-4 mb-5 fade-in-up">
        <!-- Dashboard Header -->
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">Employer Dashboard</h2>
            <p class="text-muted">Manage your job listings and track applicants.</p>
        </div>

        <!-- Total Jobs Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-index-1">
                        <h6 class="text-white text-uppercase ls-wide opacity-75 mb-3 small fw-bold">Jobs Posted</h6>
                        <h2 class="display-5 fw-bold text-white mb-0"><?php echo $total_jobs; ?></h2>
                    </div>
                    <i class="fas fa-briefcase position-absolute end-0 bottom-0 opacity-10 translate-middle-y me-4" style="font-size: 5rem; color: #fff;"></i>
                </div>
            </div>
        </div>

        <!-- Total Applications Card -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-index-1">
                        <h6 class="text-white text-uppercase ls-wide opacity-75 mb-3 small fw-bold">Apps Received</h6>
                        <h2 class="display-5 fw-bold text-white mb-0"><?php echo $total_apps; ?></h2>
                    </div>
                    <i class="fas fa-users position-absolute end-0 bottom-0 opacity-10 translate-middle-y me-4" style="font-size: 5rem; color: #fff;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row fade-in-up" style="animation-delay: 0.2s">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-4 border-0">
                    <h5 class="m-0 fw-bold text-dark"><i class="fas fa-rocket me-2 text-accent"></i>Quick Actions</h5>
                </div>
                <div class="card-body pt-0 pb-4">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="post_job.php" class="btn btn-primary px-5 py-3 shadow-sm rounded-3">
                            <i class="fas fa-plus-circle me-2"></i> Post a Job
                        </a>
                        <a href="manage_jobs.php" class="btn btn-outline-secondary border-2 px-5 py-3 rounded-3">
                            <i class="fas fa-list-check me-2"></i> Manage Listings
                        </a>
                        <a href="view_applicants.php" class="btn btn-outline-secondary border-2 px-5 py-3 rounded-3">
                            <i class="fas fa-user-graduate me-2"></i> Review Applicants
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
