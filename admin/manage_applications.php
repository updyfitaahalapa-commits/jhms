<?php
// admin/manage_applications.php
require_once '../includes/db_connect.php';
require_once 'header.php';

// Fetch all applications
$sql = "SELECT a.*, j.title as job_title, u.full_name as seeker_name, e.full_name as employer_name
        FROM applications a
        JOIN jobs j ON a.job_id = j.job_id
        JOIN users u ON a.seeker_id = u.user_id
        JOIN users e ON j.employer_id = e.user_id
        ORDER BY a.applied_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$applications = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-file-alt me-2"></i>System Applications Log</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="applicationsTable" class="table table-hover table-bordered align-middle w-100">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Seeker</th>
                            <th>Job Title</th>
                            <th>Employer</th>
                            <th>Status</th>
                            <th>Applied At</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td><?php echo $app['application_id']; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($app['seeker_name']); ?></td>
                                <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                                <td><?php echo htmlspecialchars($app['employer_name']); ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = '';
                                        if ($app['status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
                                        elseif ($app['status'] == 'Shortlisted') $badgeClass = 'bg-success';
                                        else $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge rounded-pill <?php echo $badgeClass; ?>">
                                        <?php echo $app['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                <td>
                                    <a href="../<?php echo htmlspecialchars($app['resume_path']); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
