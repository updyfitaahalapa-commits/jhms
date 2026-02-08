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

<div class="container-fluid py-4">
    <div class="row g-4 fade-in-up">
        <!-- Main Job Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill small fw-bold text-uppercase ls-wide mb-3 d-inline-block"><?php echo htmlspecialchars($job['category']); ?></span>
                            <h2 class="display-6 fw-bold text-dark mb-1"><?php echo htmlspecialchars($job['title']); ?></h2>
                            <p class="text-muted d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2 text-accent"></i> Posted on <?php echo date('M d, Y', strtotime($job['created_at'])); ?>
                            </p>
                        </div>
                        <?php if ($is_expired): ?>
                            <div class="bg-danger bg-opacity-10 text-danger px-4 py-2 rounded-4 text-center">
                                <i class="fas fa-clock fa-lg d-block mb-1"></i>
                                <small class="fw-bold text-uppercase ls-tight">Expired</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($is_expired): ?>
                        <div class="glass border-danger border-opacity-25 p-4 rounded-4 mb-5">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-info-circle text-danger fa-xl"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-danger">Waqtiga shaqadan waa dhammaaday</h6>
                                    <p class="mb-0 small text-muted">Shaqadan waqtigii loo qabtay wuxuu ku ekaa <strong><?php echo date('M d, Y H:i', strtotime($job['deadline'])); ?></strong>.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <h5 class="fw-bold mb-3 text-dark">Job Description</h5>
                    <div class="description-content text-secondary lh-lg fs-5 mb-5" style="white-space: pre-line;">
                        <?php echo htmlspecialchars($job['description']); ?>
                    </div>

                    <div class="row g-4 pt-4 border-top">
                        <div class="col-sm-6 col-md-4 text-center">
                            <div class="bg-light p-3 rounded-4">
                                <i class="fas fa-location-dot text-accent fa-xl mb-3"></i>
                                <small class="text-muted d-block mb-1 text-uppercase ls-wide">Location</small>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($job['location']); ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 text-center">
                            <div class="bg-light p-3 rounded-4">
                                <i class="fas fa-money-bill-wave text-success fa-xl mb-3"></i>
                                <small class="text-muted d-block mb-1 text-uppercase ls-wide">Salary Range</small>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($job['salary_range']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-light p-3 rounded-4">
                                <i class="fas fa-hourglass-end text-danger fa-xl mb-3"></i>
                                <small class="text-muted d-block mb-1 text-uppercase ls-wide">Deadline</small>
                                <span class="fw-bold text-dark"><?php echo date('M d, H:i', strtotime($job['deadline'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Action Area -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark">Ready to apply?</h5>
                    
                    <div class="d-grid gap-3">
                        <?php if ($is_expired): ?>
                            <div class="text-center p-4 bg-light rounded-4">
                                <i class="fas fa-lock text-muted fa-3x mb-3 animate-pulse"></i>
                                <p class="text-muted mb-0 small fw-bold text-uppercase ls-wide">Applications Closed</p>
                            </div>
                        <?php elseif ($has_applied): ?>
                            <div class="text-center p-4 bg-success bg-opacity-10 rounded-4 text-success">
                                <i class="fas fa-circle-check fa-3x mb-3"></i>
                                <p class="mb-0 fw-bold">Application Submitted</p>
                                <small class="opacity-75">You've already applied for this role.</small>
                            </div>
                        <?php else: ?>
                            <a href="apply_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-primary btn-lg py-3 rounded-3 shadow">
                                <i class="fas fa-paper-plane me-2"></i> Apply for this Role
                            </a>
                        <?php endif; ?>
                        
                        <a href="browse_jobs.php" class="btn btn-outline-secondary py-3 rounded-3 border-2">
                            <i class="fas fa-arrow-left me-2"></i> Browse more jobs
                        </a>
                    </div>

                    <div class="mt-5 pt-4 border-top">
                        <h6 class="fw-bold mb-3 text-dark">Safety Tips</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="fas fa-shield-halved text-success me-2"></i> Never pay for a job interview</li>
                            <li class="mb-2"><i class="fas fa-user-check text-info me-2"></i> Verify employer credentials</li>
                            <li><i class="fas fa-circle-info text-warning me-2"></i> Report suspicious listings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
