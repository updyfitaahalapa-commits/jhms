<?php
// employer/edit_job.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$employer_id = $_SESSION['user_id'];
$message = '';

if (!isset($_GET['id'])) {
    header("Location: manage_jobs.php");
    exit();
}

$job_id = $_GET['id'];

// Update Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $salary_range = trim($_POST['salary_range']);
    $location = trim($_POST['location']);
    $deadline = $_POST['deadline'];
    $job_id = $_POST['job_id'];

    try {
        $sql = "UPDATE jobs SET title=:title, description=:description, category=:category, salary_range=:salary_range, location=:location, deadline=:deadline 
                WHERE job_id=:job_id AND employer_id=:employer_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':salary_range', $salary_range);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':job_id', $job_id);
        $stmt->bindParam(':employer_id', $employer_id);

        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Job updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error updating job.</div>';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
    }
}

// Fetch Job Data
$stmt = $conn->prepare("SELECT * FROM jobs WHERE job_id = :job_id AND employer_id = :employer_id");
$stmt->bindParam(':job_id', $job_id);
$stmt->bindParam(':employer_id', $employer_id);
$stmt->execute();
$job = $stmt->fetch();

if (!$job) {
    echo '<div class="container"><div class="alert alert-danger">Job not found or access denied.</div></div>';
    include 'footer.php';
    exit();
}
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-edit me-2"></i>Edit Job</h4>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form action="edit_job.php?id=<?php echo $job_id; ?>" method="POST">
                <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" placeholder="Job Title" required>
                    <label for="title">Job Title *</label>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="category" name="category" required>
                                <option value="IT" <?php echo $job['category'] == 'IT' ? 'selected' : ''; ?>>IT & Software</option>
                                <option value="Marketing" <?php echo $job['category'] == 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                <option value="Finance" <?php echo $job['category'] == 'Finance' ? 'selected' : ''; ?>>Finance</option>
                                <option value="Engineering" <?php echo $job['category'] == 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                <option value="Healthcare" <?php echo $job['category'] == 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                <option value="Education" <?php echo $job['category'] == 'Education' ? 'selected' : ''; ?>>Education</option>
                                <option value="Other" <?php echo $job['category'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                            <label for="category">Category *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" placeholder="Location">
                            <label for="location">Location</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" id="description" name="description" placeholder="Job Description" style="height: 150px" required><?php echo htmlspecialchars($job['description']); ?></textarea>
                    <label for="description">Job Description *</label>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="salary_range" name="salary_range" value="<?php echo htmlspecialchars($job['salary_range']); ?>" placeholder="Salary Range">
                            <label for="salary_range">Salary Range</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="<?php echo date('Y-m-d\TH:i', strtotime($job['deadline'])); ?>" placeholder="Deadline" required>
                            <label for="deadline">Application Deadline *</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="manage_jobs.php" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Job</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
