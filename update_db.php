<?php
require_once 'includes/db_connect.php';
try {
    $conn->exec("ALTER TABLE jobs MODIFY deadline DATETIME NOT NULL");
    echo "Database updated successfully: deadline column changed to DATETIME.";
} catch(PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
