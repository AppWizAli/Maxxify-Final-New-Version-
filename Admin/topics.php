<?php
require 'config.php';
require_once 'auth_check_mcq.php';

// Fetch subjects with category and MCQ type data for dropdown
$stmt = $pdo->query("SELECT subjects.*, mcq_categories.name AS category_name, mcq_types.name AS mcq_type_name 
                     FROM subjects 
                     LEFT JOIN mcq_categories ON subjects.category_id = mcq_categories.id 
                     LEFT JOIN mcq_types ON mcq_categories.mcq_type_id = mcq_types.id 
                     ORDER BY subjects.name ASC");
$subjects = $stmt->fetchAll();

// Initialize message variables
$error = $_GET['err'] ?? '';
$message = $_GET['msg'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $subject_id = $_POST['subject_id'] ?? null;
    $id = $_POST['id'] ?? null;

    if ($name === '' || !$subject_id) {
        $error = "Please select a subject and enter a topic name.";
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE topics SET name = ?, subject_id = ? WHERE id = ?");
            $stmt->execute([$name, $subject_id, $id]);
            $message = "Topic updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO topics (name, subject_id) VALUES (?, ?)");
            $stmt->execute([$name, $subject_id]);
            $message = "Topic added successfully.";
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    try {
        $pdo->beginTransaction();

        // First, delete related MCQs
        $stmt = $pdo->prepare("DELETE FROM mcqs WHERE topic_id = ?");
        $stmt->execute([$_GET['delete']]);

        // Then, delete the topic
        $stmt = $pdo->prepare("DELETE FROM topics WHERE id = ?");
        $stmt->execute([$_GET['delete']]);

        $pdo->commit();
        header('Location: topics.php?msg=' . urlencode('Topic and related MCQs deleted successfully.'));
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $errorMsg = "Cannot delete topic. Error: " . $e->getMessage();
        header('Location: topics.php?err=' . urlencode($errorMsg));
        exit;
    }
}

// Fetch topics with subject, category, and MCQ type names
$stmt = $pdo->query("SELECT topics.*, subjects.name AS subject_name, mcq_categories.name AS category_name, mcq_types.name AS mcq_type_name 
                     FROM topics 
                     LEFT JOIN subjects ON topics.subject_id = subjects.id 
                     LEFT JOIN mcq_categories ON subjects.category_id = mcq_categories.id 
                     LEFT JOIN mcq_types ON mcq_categories.mcq_type_id = mcq_types.id 
                     ORDER BY topics.id DESC");
$topics = $stmt->fetchAll();

// Edit mode
$editTopic = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editTopic = $stmt->fetch();
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
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <div class="side-overlay"></div>

    <?php include "sidebar.php" ?>

    <div class="dashboard-main-wrapper">
        <?php include "Includes/Header.php" ?>
        <div class="dashboard-body">
            <div class="row gy-4">
                <div class="col-12 mt-4">
                    <div class="row mt-15">

                        <!-- Add/Edit Topic Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5><?= $editTopic ? 'Edit Topic' : 'Add Topic' ?></h5>
                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php elseif (!empty($message)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>

                                    <form method="post" action="topics.php">
                                        <input type="hidden" name="id" value="<?= $editTopic['id'] ?? '' ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Select Subject</label>
                                            <select name="subject_id" class="form-control" required>
                                                <option value="">-- Select Subject --</option>
                                                <?php foreach ($subjects as $subject): ?>
                                                    <option value="<?= $subject['id'] ?>" <?= ($editTopic && $editTopic['subject_id'] == $subject['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($subject['name']) ?> (<?= htmlspecialchars($subject['category_name']) ?> - <?= htmlspecialchars($subject['mcq_type_name']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Topic Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?= htmlspecialchars($editTopic['name'] ?? '') ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-10">
                                            <?= $editTopic ? 'Update' : 'Add' ?>
                                        </button>
                                        <?php if ($editTopic): ?>
                                            <a href="topics.php" class="btn btn-secondary ms-2">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Topics Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Chapters List</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>MCQ Type</th>
                                                <th>Category</th>
                                                <th>Subject</th>
                                                <th>Topic</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($topics as $t): ?>
                                                <tr>
                                                    <td style="color:black;"><?= htmlspecialchars($t['mcq_type_name'] ?? 'N/A') ?></td>
                                                    <td style="color:black;"><?= htmlspecialchars($t['category_name'] ?? 'N/A') ?></td>
                                                    <td style="color:black;"><?= htmlspecialchars($t['subject_name'] ?? 'N/A') ?></td>
                                                    <td style="color:black;"><?= htmlspecialchars($t['name']) ?></td>
                                                    <td>
                                                        <a href="?edit=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="?delete=<?= $t['id'] ?>" onclick="return confirm('Delete this topic?')" class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($topics)): ?>
                                                <tr>
                                                    <td colspan="6" style="color:black;">No topics found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /.row -->
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