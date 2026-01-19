<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userId = $_SESSION['user_id'];
$quizId = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$questionNumber = isset($_GET['q']) ? intval($_GET['q']) : 1;

if ($quizId <= 0 || $questionNumber <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
    exit();
}

try {
    // Verify quiz belongs to user
    $quizQuery = "SELECT * FROM user_quizzes WHERE id = ? AND user_id = ?";
    $quizStmt = $pdo->prepare($quizQuery);
    $quizStmt->execute([$quizId, $userId]);
    $quiz = $quizStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$quiz) {
        http_response_code(403);
        echo json_encode(['error' => 'Quiz not found']);
        exit();
    }
    
    // Get total questions for this quiz
    $totalQuery = $pdo->prepare("SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?");
    $totalQuery->execute([$quizId]);
    $totalMcqs = $totalQuery->fetchColumn();
    
    // Check if this is beyond the last question
    if ($questionNumber > $totalMcqs) {
        echo json_encode([
            'completed' => true,
            'total_questions' => $totalMcqs,
            'redirect_url' => "quizBuilderSubmission.php?quiz_id=$quizId"
        ]);
        exit();
    }
    
    // Fetch current question with MCQ details
    $questionQuery = "
        SELECT qq.question_order, m.*, t.name as topic_name
        FROM quiz_questions qq
        JOIN mcqs m ON qq.mcq_id = m.id
        LEFT JOIN topics t ON m.topic_id = t.id
        WHERE qq.quiz_id = ? AND qq.question_order = ?
    ";
    $questionStmt = $pdo->prepare($questionQuery);
    $questionStmt->execute([$quizId, $questionNumber]);
    $mcq = $questionStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$mcq) {
        echo json_encode(['error' => 'Question not found']);
        exit();
    }
    
    // Check if user has already answered this question
    $answerQuery = "SELECT selected_answer FROM quiz_answers WHERE quiz_id = ? AND user_id = ? AND question_index = ?";
    $answerStmt = $pdo->prepare($answerQuery);
    $answerStmt->execute([$quizId, $userId, $questionNumber - 1]);
    $existingAnswer = $answerStmt->fetchColumn();
    
    // Return question data
    echo json_encode([
        'success' => true,
        'question' => [
            'id' => $mcq['id'],
            'question' => $mcq['question'],
            'option_a' => $mcq['option_a'],
            'option_b' => $mcq['option_b'],
            'option_c' => $mcq['option_c'],
            'option_d' => $mcq['option_d'],
            'correct_option' => $mcq['correct_option'],
            'topic_name' => $mcq['topic_name']
        ],
        'current_question' => $questionNumber,
        'total_questions' => $totalMcqs,
        'existing_answer' => $existingAnswer,
        'quiz_name' => $quiz['deck_name']
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
