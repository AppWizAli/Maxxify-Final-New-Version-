<?php
require_once 'config.php'; // Make sure this file contains your PDO connection
require_once 'auth_check_mcq.php';
// Validate ID from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    // Invalid ID
    header("Location: view_mcqs.php?error=Invalid+MCQ+ID");
    exit;
}

// Check if MCQ exists
$stmt = $pdo->prepare("SELECT * FROM mcqs WHERE id = ?");
$stmt->execute([$id]);
$mcq = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mcq) {
    // Not found
    header("Location: view_mcqs.php?error=MCQ+not+found");
    exit;
}

// Delete the MCQ
$deleteStmt = $pdo->prepare("DELETE FROM mcqs WHERE id = ?");
$deleteStmt->execute([$id]);

// Redirect back with success message
header("Location: view_mcqs.php?deleted=1");
exit;
?>
