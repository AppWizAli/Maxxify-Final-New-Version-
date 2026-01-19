<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $deckName = $_GET['deck_name'] ?? '';
    $estimatedTime = $_GET['estimated_time'] ?? '';
    $mcqCount = intval($_GET['mcq_count'] ?? 0);
    $selectedTopics = $_GET['topics'] ?? '';

    if (empty($deckName) || empty($estimatedTime) || $mcqCount <= 0) {
        header('Location: quizBuilder.php?error=invalid_data');
        exit();
    }

    try {
        $pdo->beginTransaction();

        $checkMcqQuery = "SELECT COUNT(DISTINCT m.id) as available_mcqs FROM mcqs m WHERE 1=1";
        $checkParams = [];

        if (!empty($selectedTopics)) {
            $topicIds = explode(',', $selectedTopics);
            $placeholders = str_repeat('?,', count($topicIds) - 1) . '?';
            $checkMcqQuery .= " AND m.topic_id IN ($placeholders)";
            $checkParams = array_merge($checkParams, $topicIds);
        }

        $checkStmt = $pdo->prepare($checkMcqQuery);
        $checkStmt->execute($checkParams);
        $availableMcqs = $checkStmt->fetchColumn();

        if ($availableMcqs < $mcqCount) {
            throw new Exception("Not enough MCQs available. Requested: $mcqCount, Available: $availableMcqs");
        }

        $insertQuizQuery = "
            INSERT INTO user_quizzes (user_id, deck_name, estimated_time, mcq_count, selected_topics, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ";
        $quizStmt = $pdo->prepare($insertQuizQuery);
        $quizStmt->execute([$userId, $deckName, $estimatedTime, $mcqCount, $selectedTopics]);
        $quizId = $pdo->lastInsertId();

        $mcqQuery = "SELECT DISTINCT m.id FROM mcqs m WHERE 1=1";
        $params = [];

        if (!empty($selectedTopics)) {
            $topicIds = explode(',', $selectedTopics);
            $placeholders = str_repeat('?,', count($topicIds) - 1) . '?';
            $mcqQuery .= " AND m.topic_id IN ($placeholders)";
            $params = array_merge($params, $topicIds);
        }

        $mcqQuery .= " ORDER BY RAND() LIMIT ?";
        $params[] = $mcqCount;

        $mcqStmt = $pdo->prepare($mcqQuery);
        $mcqStmt->execute($params);
        $selectedMcqs = $mcqStmt->fetchAll(PDO::FETCH_COLUMN);

        if (count($selectedMcqs) < $mcqCount) {
            throw new Exception("Could not select enough MCQs. Requested: $mcqCount, Selected: " . count($selectedMcqs));
        }

        $insertQuestionQuery = "INSERT INTO quiz_questions (quiz_id, mcq_id, question_order) VALUES (?, ?, ?)";
        $questionStmt = $pdo->prepare($insertQuestionQuery);

        foreach ($selectedMcqs as $index => $mcqId) {
            $questionStmt->execute([$quizId, $mcqId, $index + 1]);
        }

        $pdo->commit();

        if ($quizId > 0 && count($selectedMcqs) > 0) {
            header("Location: quizBuilderMcqs.php?quiz_id=$quizId");
            exit();
        } else {
            throw new Exception("Failed to create quiz or no MCQs selected");
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: quizBuilder.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}
?> 