<?php
// employer/view_applicants.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$employer_id = $_SESSION['user_id'];
$message = '';

// Handle Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id']) && isset($_POST['status'])) {
    $status = $_POST['status'];
    $application_id = $_POST['application_id'];

    if (in_array($status, ['Pending', 'Shortlisted', 'Rejected'])) {
        try {
            // Verify application belongs to a job posted by this employer
            $check_sql = "SELECT a.application_id 
                          FROM applications a 
                          JOIN jobs j ON a.job_id = j.job_id 
                          WHERE a.application_id = :app_id AND j.employer_id = :emp_id";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bindParam(':app_id', $application_id);
            $check_stmt->bindParam(':emp_id', $employer_id);
            $check_stmt->execute();

            if ($check_stmt->rowCount() > 0) {
                $update_sql = "UPDATE applications SET status = :status WHERE application_id = :app_id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(':status', $status);
                $update_stmt->bindParam(':app_id', $application_id);
                if ($update_stmt->execute()) {
                    $message = '<div class="alert alert-success">Status updated successfully.</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">Invalid application or access denied.</div>';
            }
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch Applicants
$sql = "SELECT a.application_id, a.status, a.resume_path, a.applied_at, 
               u.full_name as seeker_name, u.email as seeker_email, 
               j.title as job_title
        FROM applications a
        JOIN users u ON a.seeker_id = u.user_id
        JOIN jobs j ON a.job_id = j.job_id
        WHERE j.employer_id = :employer_id
        ORDER BY a.applied_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':employer_id', $employer_id);
$stmt->execute();
$applicants = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-users me-2"></i>View Applicants</h4>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <div class="table-responsive">
                <table id="applicantsTable" class="table table-hover table-bordered align-middle w-100">
                    <thead class="bg-light">
                        <tr>
                            <th>Applicant Name</th>
                            <th>Email</th>
                            <th>Job Applied For</th>
                            <th>Date Applied</th>
                            <th>Resume</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($applicants) > 0): ?>
                            <?php foreach ($applicants as $app): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo htmlspecialchars($app['seeker_name']); ?></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($app['seeker_email']); ?>" class="text-decoration-none"><?php echo htmlspecialchars($app['seeker_email']); ?></a></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($app['job_title']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                                    <td>
                                        <a href="../<?php echo htmlspecialchars($app['resume_path']); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-file-pdf me-1"></i> View Resume
                                        </a>
                                    </td>
                                    <td>
                                        <?php 
                                            $badge_class = 'bg-secondary';
                                            if ($app['status'] == 'Shortlisted') $badge_class = 'bg-success';
                                            if ($app['status'] == 'Rejected') $badge_class = 'bg-danger';
                                            if ($app['status'] == 'Pending') $badge_class = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge rounded-pill <?php echo $badge_class; ?>"><?php echo $app['status']; ?></span>
                                    </td>
                                    <td>
                                        <form action="view_applicants.php" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="application_id" value="<?php echo $app['application_id']; ?>">
                                            <select name="status" class="form-select form-select-sm me-2" style="width: auto;" onchange="this.form.submit()">
                                                <option value="" disabled selected>Update</option>
                                                <option value="Shortlisted">Shortlist</option>
                                                <option value="Rejected">Reject</option>
                                                <option value="Pending">Pending</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
