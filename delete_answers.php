<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$topicId = isset($input['topic_id']) ? intval($input['topic_id']) : 0;

if ($topicId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid topic ID']);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    
    $sql = "DELETE a FROM answers a 
            JOIN mcqs m ON a.mcq_id = m.id 
            WHERE a.user_id = ? AND m.topic_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$userId, $topicId]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Answers deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete answers']);
    }
    
} catch (PDOException $e) {
    error_log('Error deleting answers: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?> 