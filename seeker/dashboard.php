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

<div class="container-fluid">
    <div class="row g-4 mb-4">
        <!-- Total Applications Card -->
        <div class="col-md-4">
            <div class="card bg-info text-white h-100 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-2">Jobs Applied</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $total_apps; ?></h2>
                    </div>
                    <i class="fas fa-paper-plane fa-4x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-primary">Recent Applications</h5>
                    <a href="browse_jobs.php" class="btn btn-sm btn-outline-primary">Find More Jobs</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Job Title</th>
                                    <th>Date Applied</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($recent_apps) > 0): ?>
                                    <?php foreach ($recent_apps as $app): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold"><?php echo htmlspecialchars($app['title']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                            <td>
                                                <?php 
                                                    $badge_class = 'bg-secondary';
                                                    if ($app['status'] == 'Shortlisted') $badge_class = 'bg-success';
                                                    if ($app['status'] == 'Rejected') $badge_class = 'bg-danger';
                                                    if ($app['status'] == 'Pending') $badge_class = 'bg-warning text-dark';
                                                ?>
                                                <span class="badge rounded-pill <?php echo $badge_class; ?>"><?php echo $app['status']; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <p class="text-muted mb-3">You haven't applied to any jobs yet.</p>
                                            <a href="browse_jobs.php" class="btn btn-primary">Start Applying</a>
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
