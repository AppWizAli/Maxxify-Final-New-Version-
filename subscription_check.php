<?php
require 'config.php';
header('Content-Type: application/json');
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
if (!$user_id) {
    echo json_encode(['status' => null]);
    exit;
}
$stmt = $pdo->prepare("SELECT status FROM subscriptions WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
if ($row) {
    echo json_encode(['status' => $row['status']]);
} else {
    echo json_encode(['status' => null]);
} 