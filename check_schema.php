<?php
require_once 'includes/db_connect.php';
$stmt = $conn->query("DESCRIBE jobs");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);
?>
