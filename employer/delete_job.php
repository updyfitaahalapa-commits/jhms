<?php
// employer/delete_job.php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $employer_id = $_SESSION['user_id'];

    try {
        // Ensure the job belongs to the current employer before deleting
        $stmt = $conn->prepare("DELETE FROM jobs WHERE job_id = :job_id AND employer_id = :employer_id");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->bindParam(':employer_id', $employer_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: manage_jobs.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting record.";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: manage_jobs.php");
    exit();
}
?>
