<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userId = $_SESSION['user_id'];

// Handle both POST form data and JSON input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quiz_id'])) {
        // Form POST data
        $quizId = intval($_POST['quiz_id'] ?? 0);
        $questionIndex = intval($_POST['question_index'] ?? 0);
        $answer = $_POST['answer'] ?? '';
    } else {
        // JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $quizId = intval($input['quiz_id'] ?? 0);
        $questionIndex = intval($input['question_index'] ?? 0);
        $answer = $input['answer'] ?? '';
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

if ($quizId <= 0 || $questionIndex < 0 || empty($answer)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit();
}

try {
    // Verify quiz belongs to user
    $quizQuery = "SELECT id FROM user_quizzes WHERE id = ? AND user_id = ?";
    $quizStmt = $pdo->prepare($quizQuery);
    $quizStmt->execute([$quizId, $userId]);
    
    if (!$quizStmt->fetch()) {
        http_response_code(403);
        echo json_encode(['error' => 'Quiz not found']);
        exit();
    }

    // Save or update answer
    $upsertQuery = "
        INSERT INTO quiz_answers (quiz_id, user_id, question_index, selected_answer, answered_at)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
        selected_answer = VALUES(selected_answer),
        answered_at = NOW()
    ";
    
    $stmt = $pdo->prepare($upsertQuery);
    $stmt->execute([$quizId, $userId, $questionIndex, $answer]);
    
    echo json_encode(['success' => true, 'message' => 'Answer saved successfully']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 