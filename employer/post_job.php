<?php
// employer/post_job.php
require_once '../includes/db_connect.php';
require_once 'header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employer_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $salary_range = trim($_POST['salary_range']);
    $location = trim($_POST['location']);
    $deadline = $_POST['deadline'];

    if (empty($title) || empty($category) || empty($description) || empty($deadline)) {
        $message = '<div class="alert alert-danger">Please fill in all required fields.</div>';
    } else {
        try {
            $sql = "INSERT INTO jobs (employer_id, title, description, category, salary_range, location, deadline) 
                    VALUES (:employer_id, :title, :description, :category, :salary_range, :location, :deadline)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':employer_id', $employer_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':salary_range', $salary_range);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':deadline', $deadline);

            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Job posted successfully! <a href="manage_jobs.php">View Jobs</a></div>';
            } else {
                $message = '<div class="alert alert-danger">Error posting job.</div>';
            }
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-0">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Post a New Job</h4>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form action="post_job.php" method="POST">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Job Title" required>
                    <label for="title">Job Title *</label>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="category" name="category" required>
                                <option value="" selected disabled>Select Category</option>
                                <option value="IT">IT & Software</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Education">Education</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="category">Category *</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="location" name="location" placeholder="Location">
                            <label for="location">Location (City, State or Remote)</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" id="description" name="description" placeholder="Job Description" style="height: 150px" required></textarea>
                    <label for="description">Job Description *</label>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="salary_range" name="salary_range" placeholder="Salary Range">
                            <label for="salary_range">Salary Range</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="deadline" name="deadline" placeholder="Deadline" required>
                            <label for="deadline">Application Deadline *</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Post Job</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
