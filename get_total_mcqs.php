<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    // Query to get total MCQs across all categories
    $query = "
        SELECT COUNT(DISTINCT m.id) as total_mcqs
        FROM mcqs m
        JOIN topics t ON m.topic_id = t.id
        JOIN subjects s ON t.subject_id = s.id
        JOIN mcq_categories c ON s.category_id = c.id
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $totalMcqs = $stmt->fetchColumn();
    
    header('Content-Type: application/json');
    echo json_encode(['totalMcqs' => $totalMcqs]);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?> 