<?php
require 'config.php';
require_once 'auth_check_mcq.php';
// Handle form submissions for Create & Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $id   = $_POST['id'] ?? null;

    if ($name === '') {
        $error = "Type name cannot be empty.";
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE mcq_types SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $message = "Updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO mcq_types (name) VALUES (?)");
            $stmt->execute([$name]);
            $message = "Added successfully.";
        }
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM mcq_types WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: mcq_types.php');
    exit;
}

// Fetch list of types
$stmt = $pdo->query("SELECT * FROM mcq_types ORDER BY id ASC");
$types = $stmt->fetchAll();

// For editing
$editType = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM mcq_types WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editType = $stmt->fetch();
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
                <div class="col-lg-9">
                    <!-- Stats Widgets (Optional: fetch with PDO) -->
                </div>

                <div class="col-lg-3">
                    <!-- Quick Actions -->
                </div>

                <!-- Main Section -->
                <div class="col-12 mt-4">
                    <div class="row">

                        <!-- Left: Add/Edit Form -->
                        <!-- <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5><?= $editType ? 'Edit Type' : 'Add MCQ Type' ?></h5>
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php elseif (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>

                                <form method="post" action="mcq_types.php">
                                    <input type="hidden" name="id" value="<?= $editType['id'] ?? '' ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Type Name</label>
                                        <input type="text" name="name" class="form-control" 
                                            value="<?= htmlspecialchars($editType['name'] ?? '') ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-10">
                                        <?= $editType ? 'Update' : 'Add' ?>
                                    </button>
                                    <?php if ($editType): ?>
                                        <a href="mcq_types.php" class="btn btn-secondary ms-2">Cancel</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div> -->

                        <!-- Right: List of Types -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Existing MCQ Types</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($types as $t): ?>
                                                <tr>
                                                    <td style="color: black;"><?= $t['id'] ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($t['name']) ?></td>
                                                    <!-- <td>
                                                    <a href="?edit=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="?delete=<?= $t['id'] ?>" onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</a>
                                                </td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($types)): ?>
                                                <tr>
                                                    <td colspan="3">No types found.</td>
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