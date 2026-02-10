<?php
// seeker/apply_job.php
require_once '../includes/db_connect.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
    header("Location: browse_jobs.php");
    exit();
}

$job_id = $_GET['id'];
$seeker_id = $_SESSION['user_id'];
$message = '';

// Fetch full job data for expiration check
$check_exp_stmt = $conn->prepare("SELECT title, deadline FROM jobs WHERE job_id = :job_id");
$check_exp_stmt->bindParam(':job_id', $job_id);
$check_exp_stmt->execute();
$job = $check_exp_stmt->fetch();

if (!$job) {
    header("Location: browse_jobs.php");
    exit();
}

$is_expired = strtotime($job['deadline']) < time();

if ($is_expired) {
    $message = '<div class="alert alert-danger shadow-sm border-0"><i class="fas fa-exclamation-circle me-2"></i><strong>Codsigu waa xidhan yahay:</strong> Shaqadan waqtigeda waa dhammaaday. (Expired: This job has expired and is no longer accepting new applications).</div>';
}

// Handle Application
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$is_expired) {
    // Check if file was uploaded without errors
    if (isset($_FILES["resume"]) && $_FILES["resume"]["error"] == 0) {
        $allowed = ["pdf" => "application/pdf", "doc" => "application/msword", "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
        $filename = $_FILES["resume"]["name"];
        $filetype = $_FILES["resume"]["type"];
        $filesize = $_FILES["resume"]["size"];
        
        // Verify extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $message = '<div class="alert alert-danger">Error: Please select a valid file format (PDF, DOC, DOCX).</div>';
        } else {
            // Verify MIME type
            if (in_array($filetype, $allowed)) {
                // Check whether file exists before uploading it
                // Create a unique name to prevent collision
                $new_filename = "resume_" . $seeker_id . "_" . $job_id . "_" . time() . "." . $ext;
                $upload_dir = "../uploads/resumes/";
                
                // Ensure directory exists
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (move_uploaded_file($_FILES["resume"]["tmp_name"], $upload_dir . $new_filename)) {
                    // Start Transaction
                    $conn->beginTransaction();

                    try {
                        // Check duplicate application again to be safe
                        $check = $conn->prepare("SELECT application_id FROM applications WHERE job_id = :job_id AND seeker_id = :seeker_id");
                        $check->bindParam(':job_id', $job_id);
                        $check->bindParam(':seeker_id', $seeker_id);
                        $check->execute();

                        if ($check->rowCount() == 0) {
                            // Insert Application
                            $resume_path = "uploads/resumes/" . $new_filename;
                            $sql = "INSERT INTO applications (job_id, seeker_id, resume_path, status) VALUES (:job_id, :seeker_id, :resume_path, 'Pending')";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':job_id', $job_id);
                            $stmt->bindParam(':seeker_id', $seeker_id);
                            $stmt->bindParam(':resume_path', $resume_path);
                            $stmt->execute();

                            $conn->commit();
                            $message = '<div class="alert alert-success">Application submitted successfully! <a href="dashboard.php">Go to Dashboard</a></div>';
                        } else {
                            $conn->rollBack();
                            $message = '<div class="alert alert-warning">You have already applied for this job.</div>';
                        }
                    } catch (PDOException $e) {
                        $conn->rollBack();
                        $message = '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
                    }
                } else {
                    $message = '<div class="alert alert-danger">Error uploading file.</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">Error: There was a problem uploading your file. Please try again.</div>';
            }
        }
    } else {
        $message = '<div class="alert alert-danger">Error: ' . $_FILES["resume"]["error"] . '</div>';
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Apply for: <?php echo htmlspecialchars($job['title']); ?></h4>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    
                    <?php if (!$is_expired && (empty($message) || strpos($message, 'alert-danger') !== false)): ?>
                        <form action="apply_job.php?id=<?php echo $job_id; ?>" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="resume" class="form-label">Upload Resume/CV (PDF, DOC, DOCX)</label>
                                <input class="form-control" type="file" id="resume" name="resume" required>
                                <div class="form-text">Max file size: 5MB.</div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">Submit Application</button>
                                <a href="view_job.php?id=<?php echo $job_id; ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    <?php elseif ($is_expired): ?>
                        <div class="text-center py-4">
                             <a href="browse_jobs.php" class="btn btn-primary px-4"><i class="fas fa-search me-2"></i>Find Other Jobs</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
