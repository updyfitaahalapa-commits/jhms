<?php
// seeker/dashboard.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$seeker_id = $_SESSION['user_id'];

// Get Total Applications
$stmt = $conn->prepare("SELECT COUNT(*) as total_apps FROM applications WHERE seeker_id = :seeker_id");
$stmt->bindParam(':seeker_id', $seeker_id, PDO::PARAM_INT);
$stmt->execute();
$total_apps = $stmt->fetch()['total_apps'];

// Get Recent Applications
$stmt = $conn->prepare("
    SELECT a.status, j.title, a.applied_at 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.job_id 
    WHERE a.seeker_id = :seeker_id 
    ORDER BY a.applied_at DESC 
    LIMIT 5
");
$stmt->bindParam(':seeker_id', $seeker_id, PDO::PARAM_INT);
$stmt->execute();
$recent_apps = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="row g-4 mb-5 fade-in-up">
        <!-- Dashboard Header -->
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">Seeker Dashboard</h2>
            <p class="text-muted">Track your job applications and career progress.</p>
        </div>
        
        <!-- Total Applications Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="position-relative z-index-1">
                        <h6 class="text-white text-uppercase ls-wide opacity-75 mb-3 small fw-bold">Active Applications</h6>
                        <h2 class="display-5 fw-bold text-white mb-0"><?php echo $total_apps; ?></h2>
                    </div>
                    <i class="fas fa-paper-plane position-absolute end-0 bottom-0 opacity-10 translate-middle-y me-4" style="font-size: 5rem; color: #fff;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row fade-in-up" style="animation-delay: 0.2s">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="m-0 fw-bold text-dark">Recent Activity</h5>
                        <small class="text-muted">Your last 5 job applications</small>
                    </div>
                    <a href="browse_jobs.php" class="btn btn-primary btn-sm px-4 shadow-sm">Find More Jobs</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="ps-4 border-0 text-muted small text-uppercase fw-bold">Opportunity</th>
                                    <th class="border-0 text-muted small text-uppercase fw-bold">Applied Date</th>
                                    <th class="border-0 text-muted small text-uppercase fw-bold text-end pe-4">Current Status</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php if (count($recent_apps) > 0): ?>
                                    <?php foreach ($recent_apps as $app): ?>
                                        <tr class="border-bottom border-light">
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($app['title']); ?></div>
                                            </td>
                                            <td class="py-3 text-muted"><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                            <td class="py-3 text-end pe-4">
                                                <?php 
                                                    $badge_class = 'bg-secondary';
                                                    if ($app['status'] == 'Shortlisted') $badge_class = 'bg-success';
                                                    if ($app['status'] == 'Rejected') $badge_class = 'bg-danger';
                                                    if ($app['status'] == 'Pending') $badge_class = 'bg-warning text-dark';
                                                ?>
                                                <span class="badge px-3 py-2 rounded-pill <?php echo $badge_class; ?> fs-7"><?php echo $app['status']; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <img src="https://illustrations.popsy.co/slate/communication.svg" width="150" alt="No apps" class="opacity-50 mb-4">
                                            <p class="text-muted mb-4 fs-5">You haven't applied to any jobs yet.</p>
                                            <a href="browse_jobs.php" class="btn btn-primary px-5 rounded-pill">Start Your Search</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
