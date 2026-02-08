<?php
// employer/manage_jobs.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$employer_id = $_SESSION['user_id'];

// Fetch jobs
$stmt = $conn->prepare("SELECT * FROM jobs WHERE employer_id = :employer_id ORDER BY created_at DESC");
$stmt->bindParam(':employer_id', $employer_id, PDO::PARAM_INT);
$stmt->execute();
$jobs = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-list me-2"></i>Manage Jobs</h4>
            <a href="post_job.php" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i> Post New Job</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="jobsTable" class="table table-hover table-bordered align-middle w-100">
                    <thead class="bg-light">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date Posted</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($jobs) > 0): ?>
                            <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td class="fw-bold text-primary"><?php echo htmlspecialchars($job['title']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($job['category']); ?></span></td>
                                    <td><?php echo date('M d, Y', strtotime($job['created_at'])); ?></td>
                                    <td>
                                        <?php if (strtotime($job['deadline']) < time()): ?>
                                            <span class="text-danger fw-bold">Expired</span>
                                        <?php else: ?>
                                            <span class="text-success"><?php echo date('M d, Y', strtotime($job['deadline'])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-jobid="<?php echo $job['job_id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this job? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="delete_job.php" method="POST" id="deleteForm">
                    <input type="hidden" name="job_id" id="modalJobId">
                    <button type="submit" class="btn btn-danger">Delete Job</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle Modal Data Transfer
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = event.relatedTarget;
            // Extract info from data-* attributes
            var jobId = button.getAttribute('data-jobid');
            // Update the modal's hidden input
            var modalInput = deleteModal.querySelector('#modalJobId');
            modalInput.value = jobId;
        });
    });
</script>

<?php require_once 'footer.php'; ?>
