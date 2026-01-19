<?php
require_once 'config.php';
require_once 'auth_check_mcq.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch($action) {
    case 'mcq_types':
        $stmt = $pdo->query("SELECT id, name FROM mcq_types ORDER BY name");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    case 'categories':
        $mcq_type_id = $_GET['mcq_type_id'] ?? '';
        if($mcq_type_id) {
            $stmt = $pdo->prepare("SELECT id, name FROM mcq_categories WHERE mcq_type_id = ? ORDER BY name");
            $stmt->execute([$mcq_type_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode([]);
        }
        break;
        
    case 'subjects':
        $category_id = $_GET['category_id'] ?? '';
        if($category_id) {
            $stmt = $pdo->prepare("SELECT id, name FROM subjects WHERE category_id = ? ORDER BY name");
            $stmt->execute([$category_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode([]);
        }
        break;
        
    case 'topics':
        $subject_id = $_GET['subject_id'] ?? '';
        if($subject_id) {
            $stmt = $pdo->prepare("SELECT id, name FROM topics WHERE subject_id = ? ORDER BY name");
            $stmt->execute([$subject_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode([]);
        }
        break;
        
    case 'mcqs':
        $topic_id = $_GET['topic_id'] ?? '';
        if($topic_id) {
            $sql = "SELECT 
                        mcqs.id, mcqs.question, mcqs.option_a, mcqs.option_b, mcqs.option_c, mcqs.option_d, mcqs.correct_option,
                        mt.name AS mcq_type_name,
                        c.name AS category_name,
                        s.name AS subject_name,
                        t.name AS topic_name
                        FROM mcqs
                        INNER JOIN topics t ON mcqs.topic_id = t.id
                        INNER JOIN subjects s ON t.subject_id = s.id
                        INNER JOIN mcq_categories c ON s.category_id = c.id
                        INNER JOIN mcq_types mt ON c.mcq_type_id = mt.id
                        WHERE mcqs.topic_id = ?
                        ORDER BY mcqs.id DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$topic_id]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } else {
            echo json_encode([]);
        }
        break;
        
    case 'all_topics_with_settings':
        $sql = "SELECT 
                    t.id as topic_id,
                    t.name AS topic_name,
                    s.name AS subject_name,
                    c.name AS category_name,
                    mt.name AS mcq_type_name,
                    COALESCE(t.is_publish, 0) AS is_publish,
                    COALESCE(t.is_free, 0) AS is_free
                    FROM topics t
                    INNER JOIN subjects s ON t.subject_id = s.id
                    INNER JOIN mcq_categories c ON s.category_id = c.id
                    INNER JOIN mcq_types mt ON c.mcq_type_id = mt.id
                    ORDER BY t.id DESC";
        $stmt = $pdo->query($sql);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>
