<?php
require 'config.php';
require_once 'auth_check_mcq.php';

// New logic for shortlistings
if (!is_dir(__DIR__ . '/uploads/shortlisting')) mkdir(__DIR__ . '/uploads/shortlisting', 0777, true);
$error = $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_name = trim($_POST['subject_name'] ?? '');
    $topic_name = trim($_POST['topic_name'] ?? '');
    $id = $_POST['id'] ?? null;
    $file_name = '';
    if ($subject_name === '' || $topic_name === '') {
        $error = 'Subject name and topic name are required.';
    } else {
        if (!empty($_FILES['notes_file']['name'])) {
            $ext = pathinfo($_FILES['notes_file']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('note_', true) . '.' . $ext;
            move_uploaded_file($_FILES['notes_file']['tmp_name'], __DIR__ . '/uploads/shortlisting/' . $file_name);
        } elseif ($id) {
            $stmt = $pdo->prepare('SELECT file_name FROM shortlistings WHERE id=?');
            $stmt->execute([$id]);
            $file_name = $stmt->fetchColumn();
        }
        if ($id) {
            $stmt = $pdo->prepare('UPDATE shortlistings SET subject_name=?, topic_name=?, file_name=? WHERE id=?');
            $stmt->execute([$subject_name, $topic_name, $file_name, $id]);
            $message = 'Updated successfully.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO shortlistings (subject_name, topic_name, file_name) VALUES (?, ?, ?)');
            $stmt->execute([$subject_name, $topic_name, $file_name]);
            $message = 'Added successfully.';
        }
    }
}
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('SELECT file_name FROM shortlistings WHERE id=?');
    $stmt->execute([$_GET['delete']]);
    $file = $stmt->fetchColumn();
    if ($file && file_exists(__DIR__ . '/uploads/shortlisting/' . $file)) unlink(__DIR__ . '/uploads/shortlisting/' . $file);
    $stmt = $pdo->prepare('DELETE FROM shortlistings WHERE id=?');
    $stmt->execute([$_GET['delete']]);
    header('Location: shortlisting.php');
    exit;
}
$editShortlisting = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM shortlistings WHERE id=?');
    $stmt->execute([$_GET['edit']]);
    $editShortlisting = $stmt->fetch();
}
$stmt = $pdo->query('SELECT * FROM shortlistings ORDER BY id ASC');
$shortlistings = $stmt->fetchAll();
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
                                    <h5><?= $editShortlisting ? 'Edit Shortlisting' : 'Add Shortlisting' ?></h5>
                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php elseif (!empty($message)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>
                                    <form method="post" enctype="multipart/form-data" action="shortlisting.php">
                                        <input type="hidden" name="id" value="<?= $editShortlisting['id'] ?? '' ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Subject Name</label>
                                            <input type="text" name="subject_name" class="form-control" value="<?= htmlspecialchars($editShortlisting['subject_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Topic Name</label>
                                            <input type="text" name="topic_name" class="form-control" value="<?= htmlspecialchars($editShortlisting['topic_name'] ?? '') ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Notes File</label>
                                            <input type="file" name="notes_file" class="form-control" <?= $editShortlisting ? '' : 'required' ?>>
                                            <?php if ($editShortlisting && $editShortlisting['file_name']): ?>
                                                <a href="uploads/shortlisting/<?= htmlspecialchars($editShortlisting['file_name']) ?>" target="_blank">Current File</a>
                                            <?php endif; ?>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-10"><?= $editShortlisting ? 'Update' : 'Add' ?></button>
                                        <?php if ($editShortlisting): ?>
                                            <a href="shortlisting.php" class="btn btn-secondary ms-2">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Right: List of Subjects -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Shortlisting Entries</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Subject Name</th>
                                                <th>Topic Name</th>
                                                <th>File</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($shortlistings as $s): ?>
                                                <tr>
                                                    <td style="color: black;"><?= $s['id'] ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($s['subject_name']) ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($s['topic_name']) ?></td>
                                                    <td style="color: black;">
                                                        <?php if ($s['file_name']): ?>
                                                            <a href="uploads/shortlisting/<?= htmlspecialchars($s['file_name']) ?>" download>Download</a> |
                                                            <a href="uploads/shortlisting/<?= htmlspecialchars($s['file_name']) ?>" target="_blank">View</a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Delete this?')" class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($shortlistings)): ?>
                                                <tr>
                                                    <td colspan="5" style="color: black;">No entries found.</td>
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