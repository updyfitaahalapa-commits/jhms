<?php
// admin/manage_jobs.php
require_once '../includes/db_connect.php';
require_once 'header.php';

// Fetch all jobs with employer name
$sql = "SELECT j.*, u.full_name as employer_name 
        FROM jobs j 
        JOIN users u ON j.employer_id = u.user_id 
        ORDER BY j.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$jobs = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-briefcase me-2"></i>Manage Jobs</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="jobsTable" class="table table-hover table-bordered align-middle w-100">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Employer</th>
                            <th>Category</th>
                            <th>Posted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo $job['job_id']; ?></td>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($job['title']); ?></td>
                                <td><?php echo htmlspecialchars($job['employer_name']); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($job['category']); ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($job['created_at'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteJobModal" 
                                            data-jobid="<?php echo $job['job_id']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteJobModal" tabindex="-1" aria-labelledby="deleteJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteJobModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="delete_job.php" method="POST">
                    <input type="hidden" name="job_id" id="modalJobId">
                    <button type="submit" class="btn btn-danger">Delete Job</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteJobModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var jobId = button.getAttribute('data-jobid');
            var modalInput = deleteModal.querySelector('#modalJobId');
            modalInput.value = jobId;
        });
    });
</script>

<?php require_once 'footer.php'; ?>
