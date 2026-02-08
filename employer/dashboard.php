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

<div class="container-fluid">
    <div class="row g-4 mb-4">
        <!-- Total Jobs Card -->
        <div class="col-md-6">
            <div class="card bg-primary text-white h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-2">Total Jobs Posted</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $total_jobs; ?></h2>
                    </div>
                    <i class="fas fa-briefcase fa-4x text-white-50"></i>
                </div>
            </div>
        </div>

        <!-- Total Applications Card -->
        <div class="col-md-6">
            <div class="card bg-success text-white h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-2">Applications Received</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $total_apps; ?></h2>
                    </div>
                    <i class="fas fa-users fa-4x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="m-0 fw-bold text-primary"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="post_job.php" class="btn btn-primary btn-lg me-3 mb-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i> Post a New Job
                    </a>
                    <a href="manage_jobs.php" class="btn btn-outline-primary btn-lg mb-2">
                        <i class="fas fa-list me-2"></i> Manage Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
