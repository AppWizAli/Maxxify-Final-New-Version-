<?php
session_start();
include 'config.php';

// Make sure user is logged in
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
  die('You must be logged in to answer questions.');
}

// Get submitted data
$mcq_id = $_POST['mcq_id'];
$selected_option = $_POST['selected_option'];
$topic_id = $_POST['topic_id'];
$mode = $_POST['mode'];
$current_question = $_POST['current_question'];

$stmt = $pdo->prepare("SELECT correct_option FROM mcqs WHERE id = ?");
$stmt->execute([$mcq_id]);
$correct_option = $stmt->fetchColumn();

$is_correct = (strtolower($selected_option) === strtolower($correct_option)) ? 1 : 0;

// Count total MCQs by topic_id
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM mcqs WHERE topic_id = ?");
$totalQuery->execute([$topic_id]);
$totalMcqs = $totalQuery->fetchColumn();

$check = $pdo->prepare("SELECT id FROM answers WHERE user_id = ? AND mcq_id = ?");
$check->execute([$user_id, $mcq_id]);

if ($check->rowCount()) {
  $update = $pdo->prepare("UPDATE answers SET selected_option = ?, is_correct = ?, created_at = NOW() WHERE user_id = ? AND mcq_id = ?");
  $update->execute([$selected_option, $is_correct, $user_id, $mcq_id]);
} else {
  $insert = $pdo->prepare("INSERT INTO answers (user_id, mcq_id, selected_option, is_correct) VALUES (?, ?, ?, ?)");
  $insert->execute([$user_id, $mcq_id, $selected_option, $is_correct]);
}

// Insert into free_attempts only if user does NOT have a valid subscription (any valid subscription for user_id)
$subStmt = $pdo->prepare("SELECT end_date, status FROM subscriptions WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$subStmt->execute([$user_id]);
$subRow = $subStmt->fetch();
$hasValidSub = false;
if ($subRow && $subRow['status'] === 'approved' && $subRow['end_date'] && $subRow['end_date'] >= date('Y-m-d')) {
  $hasValidSub = true;
}
if (!$hasValidSub) {
  $userTopicCheck = $pdo->prepare("SELECT id FROM free_attempts WHERE user_id = ? AND topic_id = ?");
  $userTopicCheck->execute([$user_id, $topic_id]);
  if ($userTopicCheck->rowCount() == 0) {
    $userTopicInsert = $pdo->prepare("INSERT INTO free_attempts (user_id, topic_id) VALUES (?, ?)");
    $userTopicInsert->execute([$user_id, $topic_id]);
  }
}

// Check if this is the last question
if ($current_question >= $totalMcqs) {
  echo "no_more=1";
  exit;
}

// If not the last question, return the next question number
$nextQuestion = $current_question + 1;
echo "next_question=$nextQuestion";
exit;
?>