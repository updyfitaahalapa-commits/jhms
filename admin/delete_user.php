<?php
// admin/delete_user.php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    try {
        // Prevent deleting self (though UI hides button, backend check is good)
        if ($user_id == $_SESSION['user_id']) {
            die("Cannot delete yourself.");
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: manage_users.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting user.";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: manage_users.php");
    exit();
}
?>
