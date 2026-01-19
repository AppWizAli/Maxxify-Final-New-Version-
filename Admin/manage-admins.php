<?php
require 'config.php';
require_once 'auth_check.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'admin';
    $id = $_POST['id'] ?? null;

    if ($username === '' || $email === '' || ($id === null && $password === '')) {
        $error = "All fields are required.";
    } else {
        if ($id) {
            // Update (without changing password if left blank)
            if ($password !== '') {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $email, $password_hash, $role, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, role = ? WHERE id = ?");
                $stmt->execute([$username, $email, $role, $id]);
            }
            $message = "Admin updated successfully.";
        } else {
            // Insert
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash, $role]);
            $message = "Admin added successfully.";
        }
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: manage-admins.php");
    exit;
}

// Handle editing
$editAdmin = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editAdmin = $stmt->fetch();
}

// Fetch all admins
$stmt = $pdo->query("SELECT * FROM admins ORDER BY id DESC");
$admins = $stmt->fetchAll();
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
                                    <h5><?= $editAdmin ? 'Edit Admin' : 'Add Admin' ?></h5>
                                    <?php if (!empty($error)): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                    <?php elseif (!empty($message)): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                    <?php endif; ?>

                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= $editAdmin['id'] ?? '' ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($editAdmin['username'] ?? '') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editAdmin['email'] ?? '') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label"><?= $editAdmin ? 'New Password (leave blank to keep existing)' : 'Password' ?></label>
                                            <input type="password" name="password" class="form-control" <?= $editAdmin ? '' : 'required' ?>>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-control" required>
                                                <option value="admin" <?= (isset($editAdmin['role']) && $editAdmin['role'] === 'admin') ? 'selected' : '' ?>>admin</option>
                                                <option value="mcq_management" <?= (isset($editAdmin['role']) && $editAdmin['role'] === 'mcq_management') ? 'selected' : '' ?>>mcq_management</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary"><?= $editAdmin ? 'Update' : 'Add' ?></button>
                                        <?php if ($editAdmin): ?>
                                            <a href="admin_manage.php" class="btn btn-secondary ms-2">Cancel</a>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Right: List of Admins -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5>All Admins</h5>
                                    <table class="table table-striped mt-2">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($admins as $admin): ?>
                                                <tr>
                                                    <td style="color:black;"><?= $admin['id'] ?></td>
                                                    <td style="color:black;"><?= htmlspecialchars($admin['username']) ?></td>
                                                    <td style="color:black;"><?= htmlspecialchars($admin['email']) ?></td>
                                                    <td style="color:black;"><?= htmlspecialchars($admin['role'] ?? 'admin') ?></td>
                                                    <td style="color:black;"><?= $admin['created_at'] ?></td>
                                                    <td>
                                                        <a href="?edit=<?= $admin['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <a href="?delete=<?= $admin['id'] ?>" onclick="return confirm('Delete this admin?')" class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <?php if (empty($admins)): ?>
                                                <tr>
                                                    <td colspan="6">No admins found.</td>
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