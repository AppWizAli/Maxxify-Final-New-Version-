<?php 
require 'config.php';
require_once 'auth_check.php';
// Handle form submissions for Create & Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $id   = $_POST['id'] ?? null;

    if ($name === '') {
        $error = "Chapter name cannot be empty.";
    } else {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE chapters SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $message = "Chapter updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO chapters (name) VALUES (?)");
            $stmt->execute([$name]);
            $message = "Chapter added successfully.";
        }
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM chapters WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: chapters.php');
    exit;
}

// Fetch list of chapters
$stmt = $pdo->query("SELECT * FROM chapters ORDER BY id DESC");
$chapters = $stmt->fetchAll();

// For editing
$editChapter = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM chapters WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editChapter = $stmt->fetch();
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
                                <h5><?= $editChapter ? 'Edit Chapter' : 'Add Chapter' ?></h5>
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php elseif (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>

                                <form method="post" action="chapters.php">
                                    <input type="hidden" name="id" value="<?= $editChapter['id'] ?? '' ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Subject Name</label>
                                        <input type="text" name="name" class="form-control" 
                                            value="<?= htmlspecialchars($editChapter['name'] ?? '') ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-10">
                                        <?= $editChapter ? 'Update' : 'Add' ?>
                                    </button>
                                    <?php if ($editChapter): ?>
                                        <a href="chapters.php" class="btn btn-secondary ms-2">Cancel</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right: List of Chapters -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5>Existing Subjects</h5>
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($chapters as $chapter): ?>
                                            <tr>
                                                <td style="color: black;"><?= $chapter['id'] ?></td>
                                                <td style="color: black;"><?= htmlspecialchars($chapter['name']) ?></td>
                                                <td>
                                                    <a href="?edit=<?= $chapter['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="?delete=<?= $chapter['id'] ?>" onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($chapters)): ?>
                                            <tr><td colspan="3" style="color: black;">No chapters found.</td></tr>
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