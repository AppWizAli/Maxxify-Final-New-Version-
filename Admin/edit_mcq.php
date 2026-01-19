<?php
require_once 'config.php'; // Your PDO connection
require_once 'auth_check_mcq.php';

// Get MCQ ID from query
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Invalid MCQ ID.');
}

// Fetch existing MCQ
$stmt = $pdo->prepare("SELECT * FROM mcqs WHERE id = ?");
$stmt->execute([$id]);
$mcq = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mcq) {
    die('MCQ not found.');
}

// Fetch dropdown data with hierarchy
$sql = "SELECT 
            t.id AS topic_id,
            t.name AS topic_name,
            s.id AS subject_id,
            s.name AS subject_name,
            c.id AS category_id,
            c.name AS category_name,
            mt.id AS mcq_type_id,
            mt.name AS mcq_type_name
        FROM topics t
        JOIN subjects s ON t.subject_id = s.id
        JOIN mcq_categories c ON s.category_id = c.id
        JOIN mcq_types mt ON c.mcq_type_id = mt.id
        ORDER BY mt.name, c.name, s.name, t.name";

$topics_hierarchy = $pdo->query($sql)->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $topic_id = $_POST['topic_id'];
    $option_a_expl = $_POST['option_a_explanation'] ?? null;
    $option_b_expl = $_POST['option_b_explanation'] ?? null;
    $option_c_expl = $_POST['option_c_explanation'] ?? null;
    $option_d_expl = $_POST['option_d_explanation'] ?? null;
    $explanation = $_POST['explanation'] ?? null;

    $update = $pdo->prepare("UPDATE mcqs SET 
        question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ?, 
        topic_id = ?,
        explanation = ?, 
        option_a_explanation = ?, option_b_explanation = ?, 
        option_c_explanation = ?, option_d_explanation = ?
        WHERE id = ?");

    $update->execute([
        $question,
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $correct_option,
        $topic_id,
        $explanation,
        $option_a_expl,
        $option_b_expl,
        $option_c_expl,
        $option_d_expl,
        $id
    ]);

    header("Location: view_mcqs.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Maxxify Academy</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/file-upload.css">
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/plyr.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <!-- full calendar -->
    <link rel="stylesheet" href="assets/css/full-calendar.css">
    <!-- jquery Ui -->
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <!-- editor quill Ui -->
    <link rel="stylesheet" href="assets/css/editor-quill.css">
    <!-- apex charts Css -->
    <link rel="stylesheet" href="assets/css/apexcharts.css">
    <!-- calendar Css -->
    <link rel="stylesheet" href="assets/css/calendar.css">
    <!-- jvector map Css -->
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

    <!--==================== Preloader Start ====================-->
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <!--==================== Preloader End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- ============================ Sidebar Start ============================ -->

    <?php include "sidebar.php" ?>
    <!-- ============================ Sidebar End  ============================ -->

    <div class="dashboard-main-wrapper">
        <?php include "Includes/Header.php" ?>
        
        <div class="dashboard-body">
            <div class="row gy-4">
            <div class="col-md-8 offset-md-2 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Edit MCQ (ID: <?= $id ?>)</h5>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Question</label>
                                <textarea name="question" class="form-control" required><?= htmlspecialchars($mcq['question']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Option A</label>
                                <input type="text" name="option_a" class="form-control" value="<?= htmlspecialchars($mcq['option_a']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Option B</label>
                                <input type="text" name="option_b" class="form-control" value="<?= htmlspecialchars($mcq['option_b']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Option C</label>
                                <input type="text" name="option_c" class="form-control" value="<?= htmlspecialchars($mcq['option_c']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Option D</label>
                                <input type="text" name="option_d" class="form-control" value="<?= htmlspecialchars($mcq['option_d']) ?>" required>
                            </div>
                            <!-- Explanation for Option A -->
                            <div class="mb-3">
                                <label class="form-label">Explanation for Option A</label>
                                <textarea name="option_a_explanation" class="form-control"><?= htmlspecialchars($mcq['option_a_explanation']) ?></textarea>
                            </div>

                            <!-- Explanation for Option B -->
                            <div class="mb-3">
                                <label class="form-label">Explanation for Option B</label>
                                <textarea name="option_b_explanation" class="form-control"><?= htmlspecialchars($mcq['option_b_explanation']) ?></textarea>
                            </div>

                            <!-- Explanation for Option C -->
                            <div class="mb-3">
                                <label class="form-label">Explanation for Option C</label>
                                <textarea name="option_c_explanation" class="form-control"><?= htmlspecialchars($mcq['option_c_explanation']) ?></textarea>
                            </div>

                            <!-- Explanation for Option D -->
                            <div class="mb-3">
                                <label class="form-label">Explanation for Option D</label>
                                <textarea name="option_d_explanation" class="form-control"><?= htmlspecialchars($mcq['option_d_explanation']) ?></textarea>
                            </div>

                            <!-- Main Explanation -->
                            <div class="mb-3">
                                <label class="form-label">General Explanation</label>
                                <textarea name="explanation" class="form-control"><?= htmlspecialchars($mcq['explanation']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correct Option</label>
                                <select name="correct_option" class="form-select" required>
                                    <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $mcq['correct_option'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select Topic</label>
                                <select name="topic_id" class="form-select" required>
                                    <option value="">Select Topic</option>
                                    <?php foreach ($topics_hierarchy as $item): ?>
                                        <option value="<?= $item['topic_id'] ?>" <?= $mcq['topic_id'] == $item['topic_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($item['mcq_type_name']) ?> →
                                            <?= htmlspecialchars($item['category_name']) ?> →
                                            <?= htmlspecialchars($item['subject_name']) ?> →
                                            <?= htmlspecialchars($item['topic_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Update MCQ</button>
                            <a href="view_mcqs.php" class="btn btn-secondary ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Jquery js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <!-- Phosphor Js -->
    <script src="assets/js/phosphor-icon.js"></script>
    <!-- file upload -->
    <script src="assets/js/file-upload.js"></script>
    <!-- file upload -->
    <script src="assets/js/plyr.js"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <!-- full calendar -->
    <script src="assets/js/full-calendar.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/jquery-ui.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/editor-quill.js"></script>
    <!-- apex charts -->
    <script src="assets/js/apexcharts.min.js"></script>
    <!-- Calendar Js -->
    <script src="assets/js/calendar.js"></script>
    <!-- jvectormap Js -->
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <!-- jvectormap world Js -->
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>

    <!-- main js -->
    <script src="assets/js/main.js"></script>


</body>

</html>