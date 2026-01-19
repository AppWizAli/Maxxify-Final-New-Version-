<?php
require 'config.php';
require_once 'auth_check_mcq.php';

// Fetch MCQ categories for dropdown
$stmt = $pdo->query("SELECT mcq_categories.*, mcq_types.name AS mcq_type_name FROM mcq_categories LEFT JOIN mcq_types ON mcq_categories.mcq_type_id = mcq_types.id ORDER BY mcq_categories.name ASC");
$mcq_categories = $stmt->fetchAll();

// Handle form submissions for Create & Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $id   = $_POST['id'] ?? null;

    if ($name === '' || $category_id === '') {
        $error = "Subject name and category are required.";
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE subjects SET name = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$name, $category_id, $id]);
            $message = "Subject updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO subjects (name, category_id) VALUES (?, ?)");
            $stmt->execute([$name, $category_id]);
            $message = "Subject added successfully.";
        }
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: subjects.php');
    exit;
}

// Fetch list of subjects with category and MCQ type names
$stmt = $pdo->query("SELECT subjects.*, mcq_categories.name AS category_name, mcq_types.name AS mcq_type_name FROM subjects LEFT JOIN mcq_categories ON subjects.category_id = mcq_categories.id LEFT JOIN mcq_types ON mcq_categories.mcq_type_id = mcq_types.id ORDER BY subjects.id DESC");
$subjects = $stmt->fetchAll();

// For editing
$editSubject = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editSubject = $stmt->fetch();
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
                <div class="col-12 mt-4">
                    <div class="row">

                        <!-- Left: Add/Edit Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5><?= $editSubject ? 'Edit Subject' : 'Add Subject' ?></h5>
                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php elseif (!empty($message)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>

                                    <form method="post" action="subjects.php">
                                        <input type="hidden" name="id" value="<?= $editSubject['id'] ?? '' ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select name="category_id" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($mcq_categories as $category): ?>
                                                    <option value="<?= $category['id'] ?>" <?= (isset($editSubject['category_id']) && $editSubject['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($category['name']) ?> (<?= htmlspecialchars($category['mcq_type_name']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subject Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="<?= htmlspecialchars($editSubject['name'] ?? '') ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-10">
                                            <?= $editSubject ? 'Update' : 'Add' ?>
                                        </button>
                                        <?php if ($editSubject): ?>
                                            <a href="subjects.php" class="btn btn-secondary ms-2">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Right: List of Subjects -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Existing Subjects</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>MCQ Type</th>
                                                <th>Subject Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($subjects as $s): ?>
                                                <tr>
                                                    <td style="color: black;"><?= htmlspecialchars($s['category_name'] ?? 'N/A') ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($s['mcq_type_name'] ?? 'N/A') ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($s['name']) ?></td>
                                                    <td>
                                                        <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($subjects)): ?>
                                                <tr>
                                                    <td colspan="5" style="color: black;">No subjects found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /.row -->
                </div> <!-- /.col-12 -->
            </div> <!-- /.row gy-4 -->
        </div> <!-- /.dashboard-body -->

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