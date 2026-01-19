<?php
require 'config.php';
require_once 'auth_check.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = $_POST['category'] ?? '';
    $marks = $_POST['marks'] ?? '';
    $id = $_POST['id'] ?? null;
    $imagePath = '';

    if ($name === '' || $category === '' || $marks === '') {
        $error = "All fields are required.";
    } else {
        if ($_FILES['image']['name']) {
            $uploadDir = 'uploads/';
            $imagePath = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        if ($id) {
            // Update
            if ($imagePath !== '') {
                $stmt = $pdo->prepare("UPDATE high_achievers SET name = ?, category = ?, marks = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $category, $marks, $imagePath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE high_achievers SET name = ?, category = ?, marks = ? WHERE id = ?");
                $stmt->execute([$name, $category, $marks, $id]);
            }
            $message = "Student updated successfully.";
        } else {
            // Insert
            if ($imagePath === '') {
                $error = "Please upload a student image.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO high_achievers (name, category, marks, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $category, $marks, $imagePath]);
                $message = "Student added successfully.";
            }
        }
    }
}

// Handle Top Rated action
if (isset($_GET['top_rated'])) {
    $studentId = intval($_GET['top_rated']);
    $stmt = $pdo->prepare("UPDATE high_achievers SET top_rated = 1 WHERE id = ?");
    $stmt->execute([$studentId]);
    header("Location: manage_high_achievers.php"); // update with your file name
    exit;
}

// Delete student
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM high_achievers WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: manage_achievers.php");
    exit;
}

// Edit student
$editStudent = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM high_achievers WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editStudent = $stmt->fetch();
}

// Get all students
$stmt = $pdo->query("SELECT * FROM high_achievers ORDER BY id DESC");
$students = $stmt->fetchAll();
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

                        <!-- Left: Add/Edit Student -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5><?= $editStudent ? 'Edit Student' : 'Add High Achiever' ?></h5>

                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php elseif (!empty($message)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>

                                    <form method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $editStudent['id'] ?? '' ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Student Name</label>
                                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editStudent['name'] ?? '') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select name="category" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <?php foreach (['MDCAT', 'NUMS', 'FSC'] as $cat): ?>
                                                    <option value="<?= $cat ?>" <?= ($editStudent['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Obtained Marks</label>
                                            <input type="number" name="marks" class="form-control" value="<?= $editStudent['marks'] ?? '' ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Student Image <?= $editStudent ? '(optional)' : '' ?></label>
                                            <input type="file" name="image" class="form-control" <?= $editStudent ? '' : 'required' ?>>
                                            <?php if ($editStudent && $editStudent['image']): ?>
                                                <img src="<?= htmlspecialchars($editStudent['image']) ?>" alt="Student" class="img-thumbnail mt-2" width="100">
                                            <?php endif; ?>
                                        </div>

                                        <button type="submit" class="btn btn-primary"><?= $editStudent ? 'Update' : 'Add' ?></button>
                                        <?php if ($editStudent): ?>
                                            <a href="manage_achievers.php" class="btn btn-secondary ms-2">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Student Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5>All High Achievers</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Marks</th>
                                                <th>Image</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td style="color: black;"><?= $student['id'] ?></td>
                                                    <td style="color: black;"><?= htmlspecialchars($student['name']) ?></td>
                                                    <td style="color: black;"><?= $student['category'] ?></td>
                                                    <td style="color: black;"><?= $student['marks'] ?></td>
                                                    <td><img src="<?= htmlspecialchars($student['image']) ?>" width="50" class="img-thumbnail"></td>
                                                    <td>
                                                        <a href="?edit=<?= $student['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="?delete=<?= $student['id'] ?>" onclick="return confirm('Delete this student?')" class="btn btn-sm btn-danger">Delete</a>
                                                        <?php if (!$student['top_rated']): ?>
                                                            <a href="?top_rated=<?= $student['id'] ?>" class="btn btn-sm btn-success">Top Rated</a>
                                                        <?php else: ?>
                                                            <span class="badge bg-success">Top Rated</span>
                                                        <?php endif; ?>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($students)): ?>
                                                <tr>
                                                    <td colspan="6">No students found.</td>
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