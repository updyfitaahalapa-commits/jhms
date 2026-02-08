<?php
// seeker/view_job.php
require_once '../includes/db_connect.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    header("Location: browse_jobs.php");
    exit();
}

$job_id = $_GET['id'];
$seeker_id = $_SESSION['user_id'];

// Fetch Job Details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id = :job_id");
$stmt->bindParam(':job_id', $job_id);
$stmt->execute();
$job = $stmt->fetch();

if (!$job) {
    echo '<div class="container"><div class="alert alert-danger">Job not found.</div></div>';
    include 'footer.php';
    exit();
}

// Check if already applied
$check_stmt = $conn->prepare("SELECT application_id FROM applications WHERE job_id = :job_id AND seeker_id = :seeker_id");
$check_stmt->bindParam(':job_id', $job_id);
$check_stmt->bindParam(':seeker_id', $seeker_id);
$check_stmt->execute();
$has_applied = $check_stmt->rowCount() > 0;

// Expiration Logic (Precise Time)
$is_expired = strtotime($job['deadline']) < time();
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h4 class="m-0 fw-bold text-primary">
                <i class="fas fa-briefcase me-2"></i><?php echo htmlspecialchars($job['title']); ?>
                <?php if ($is_expired): ?>
                    <span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle me-1"></i> Waqtigu waa dhammaaday</span>
                <?php endif; ?>
            </h4>
            <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($job['category']); ?></span>
        </div>
        <div class="card-body">
            <?php if ($is_expired): ?>
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="fas fa-info-circle fa-2x me-3 text-warning"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Waqtiga shaqadan waa dhammaaday (Expired)</h6>
                        <p class="mb-0 small text-muted">Shaqadan waqtigii loo qabtay wuxuu ku ekaa <strong><?php echo date('M d, Y \k\u bimaneyso H:i', strtotime($job['deadline'])); ?></strong>. Hadda codsiyo cusub lama aqbalayo.</p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-map-marker-alt text-secondary me-3 fa-lg"></i>
                        <div>
                            <small class="text-muted d-block">Location</small>
                            <strong><?php echo htmlspecialchars($job['location']); ?></strong>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-money-bill-wave text-success me-3 fa-lg"></i>
                        <div>
                            <small class="text-muted d-block">Salary Range</small>
                            <strong><?php echo htmlspecialchars($job['salary_range']); ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-calendar-alt text-info me-3 fa-lg"></i>
                        <div>
                            <small class="text-muted d-block">Posted On</small>
                            <strong><?php echo date('M d, Y', strtotime($job['created_at'])); ?></strong>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hourglass-end text-danger me-3 fa-lg"></i>
                        <div>
                            <small class="text-muted d-block">Deadline</small>
                            <strong><?php echo date('M d, Y H:i', strtotime($job['deadline'])); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="text-muted opacity-25">
            
            <h5 class="fw-bold mb-3">Job Description</h5>
            <div class="p-3 bg-light rounded border-0">
                <p class="mb-0" style="white-space: pre-line;"><?php echo htmlspecialchars($job['description']); ?></p>
            </div>
            
            <hr class="text-muted opacity-25 my-4">
            
            <div class="text-center">
                <?php if ($is_expired): ?>
                    <button class="btn btn-secondary btn-lg px-5 disabled" disabled title="This job is no longer accepting applications">
                        <i class="fas fa-clock me-2"></i> Application Period Ended
                    </button>
                <?php elseif ($has_applied): ?>
                    <button class="btn btn-success btn-lg px-5 disabled" disabled>
                        <i class="fas fa-check-circle me-2"></i> Application Submitted
                    </button>
                <?php else: ?>
                    <a href="apply_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> Apply Now
                    </a>
                <?php endif; ?>
                <a href="browse_jobs.php" class="btn btn-outline-secondary btn-lg ms-3 px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back to Jobs
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
