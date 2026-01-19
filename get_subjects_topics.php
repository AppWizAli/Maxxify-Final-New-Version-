<?php
session_start();
include 'config.php';

// Get the selected category
$category = $_GET['category'] ?? 'mdcat';

// Map category names to mcq_type_id
$categoryMap = [
    'mdcat' => 1,
    'nums' => 2,
    'fsc' => 3
];

$mcqTypeId = $categoryMap[$category] ?? 1;

// Fetch subjects and topics based on the selected category
$query = "
    SELECT 
        s.id as subject_id,
        s.name as subject_name,
        t.id as topic_id,
        t.name as topic_name,
        COUNT(m.id) as mcq_count
    FROM subjects s
    LEFT JOIN topics t ON s.id = t.subject_id
    LEFT JOIN mcqs m ON t.id = m.topic_id
    LEFT JOIN mcq_categories c ON s.category_id = c.id
    WHERE c.mcq_type_id = ?
    GROUP BY s.id, s.name, t.id, t.name
    ORDER BY s.name, t.name
";

$stmt = $pdo->prepare($query);
$stmt->execute([$mcqTypeId]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by subjects
$subjects = [];
foreach ($results as $row) {
    $subjectId = $row['subject_id'];
    if (!isset($subjects[$subjectId])) {
        $subjects[$subjectId] = [
            'name' => $row['subject_name'],
            'topics' => []
        ];
    }
    
    if ($row['topic_id']) {
        $subjects[$subjectId]['topics'][] = [
            'id' => $row['topic_id'],
            'name' => $row['topic_name'],
            'mcq_count' => $row['mcq_count']
        ];
    }
}

// Calculate total MCQs available
$totalMcqQuery = "
    SELECT COUNT(m.id) as total_mcqs
    FROM mcqs m
    JOIN topics t ON m.topic_id = t.id
    JOIN subjects s ON t.subject_id = s.id
    JOIN mcq_categories c ON s.category_id = c.id
    WHERE c.mcq_type_id = ?
";

$totalStmt = $pdo->prepare($totalMcqQuery);
$totalStmt->execute([$mcqTypeId]);
$totalMcqs = $totalStmt->fetchColumn();

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'subjects' => array_values($subjects),
    'totalMcqs' => $totalMcqs
]);
?> 