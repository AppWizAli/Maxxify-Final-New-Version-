<?php
require_once 'config.php';
require_once 'auth_check_mcq.php';

header('Content-Type: application/json');

$topic_id = $_GET['topic_id'] ?? '';

if($topic_id) {
    $stmt = $pdo->prepare("SELECT is_publish, is_free FROM topics WHERE id = ?");
    $stmt->execute([$topic_id]);
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($settings) {
        echo json_encode([
            'success' => true,
            'settings' => [
                'is_publish' => $settings['is_publish'],
                'is_free' => $settings['is_free']
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Topic not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Topic ID is required'
    ]);
}
?>
