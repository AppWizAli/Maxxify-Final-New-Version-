<?php
require 'config.php';
require_once 'auth_check_mcq.php';

// Fetch MCQ types for dropdown
$stmt = $pdo->query("SELECT * FROM mcq_types ORDER BY name ASC");
$mcq_types = $stmt->fetchAll();

// Handle form submissions for Create & Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $mcq_type_id = $_POST['mcq_type_id'] ?? '';
    $id   = $_POST['id'] ?? null;

    if ($name === '' || $mcq_type_id === '') {
        $error = "Category name and MCQ type are required.";
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE mcq_categories SET name = ?, mcq_type_id = ? WHERE id = ?");
            $stmt->execute([$name, $mcq_type_id, $id]);
            $message = "Category updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO mcq_categories (name, mcq_type_id) VALUES (?, ?)");
            $stmt->execute([$name, $mcq_type_id]);
            $message = "Category added successfully.";
        }
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM mcq_categories WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: mcq_categories.php');
    exit;
}

// Fetch list of categories with MCQ type names
$stmt = $pdo->query("SELECT mcq_categories.*, mcq_types.name AS mcq_type_name FROM mcq_categories LEFT JOIN mcq_types ON mcq_categories.mcq_type_id = mcq_types.id ORDER BY mcq_categories.id ASC");
$categories = $stmt->fetchAll();

// For editing
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM mcq_categories WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCategory = $stmt->fetch();
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
                        <!-- <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5><?= $editCategory ? 'Edit Category' : 'Add MCQ Category' ?></h5>
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php elseif (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>

                                <form method="post" action="mcq_categories.php">
                                    <input type="hidden" name="id" value="<?= $editCategory['id'] ?? '' ?>">
                                    <div class="mb-3">
                                        <label class="form-label">MCQ Type</label>
                                        <select name="mcq_type_id" class="form-control" required>
                                            <option value="">Select MCQ Type</option>
                                            <?php foreach ($mcq_types as $type): ?>
                                                <option value="<?= $type['id'] ?>" <?= (isset($editCategory['mcq_type_id']) && $editCategory['mcq_type_id'] == $type['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($type['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" name="name" class="form-control" 
                                            value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-10">
                                        <?= $editCategory ? 'Update' : 'Add' ?>
                                    </button>
                                    <?php if ($editCategory): ?>
                                        <a href="mcq_categories.php" class="btn btn-secondary ms-2">Cancel</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div> -->

                        <!-- Right: List of Categories -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Existing MCQ Categories</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>MCQ Type</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $c): ?>
                                                <tr>
                                                    <td style="color: black;"><?= $c['id'] ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($c['mcq_type_name'] ?? 'N/A') ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($c['name']) ?></td>
                                                    <!-- <td>
                                                    <a href="?edit=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="?delete=<?= $c['id'] ?>" onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</a>
                                                </td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($categories)): ?>
                                                <tr>
                                                    <td colspan="4" style="color: black;">No categories found.</td>
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