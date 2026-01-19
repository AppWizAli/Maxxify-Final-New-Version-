<?php
require_once 'config.php';
require_once 'auth_check_mcq.php';

header('Content-Type: application/json');

$topic_id = $_POST['topic_id'] ?? '';
$is_publish = $_POST['is_publish'] ?? '';
$is_free = $_POST['is_free'] ?? '';

if($topic_id && $is_publish !== '' && $is_free !== '') {
    try {
        $stmt = $pdo->prepare("UPDATE topics SET is_publish = ?, is_free = ? WHERE id = ?");
        $result = $stmt->execute([$is_publish, $is_free, $topic_id]);
        
        if($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update settings'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
}
?>
