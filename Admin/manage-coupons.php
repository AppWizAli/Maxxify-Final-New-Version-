<?php
require_once 'auth_check.php';
require 'config.php';

// Fetch all teachers for dropdown
$stmt = $pdo->query("SELECT id, name FROM teachers ORDER BY name ASC");
$teachers = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_POST['teacher_id'] ?? '';
    $code = trim($_POST['code'] ?? '');
    $percentage = trim($_POST['percentage'] ?? '');
    $expiry_date = $_POST['expiry_date'] ?? '';
    $id = $_POST['id'] ?? null;

    if ($teacher_id === '' || $code === '' || $percentage === '' || $expiry_date === '') {
        $error = "All fields are required.";
    } else {
        // Check for unique code
        if ($id) {
            $stmt = $pdo->prepare("SELECT id FROM coupons WHERE code = ? AND id != ?");
            $stmt->execute([$code, $id]);
        } else {
            $stmt = $pdo->prepare("SELECT id FROM coupons WHERE code = ?");
            $stmt->execute([$code]);
        }
        if ($stmt->fetch()) {
            $error = "Coupon already exists.";
        } else {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE coupons SET teacher_id = ?, code = ?, percentage = ?, expiry_date = ? WHERE id = ?");
                $stmt->execute([$teacher_id, $code, $percentage, $expiry_date, $id]);
                header("Location: manage-coupons.php?success=2");
                exit;
            } else {
                $stmt = $pdo->prepare("INSERT INTO coupons (teacher_id, code, percentage, expiry_date) VALUES (?, ?, ?, ?)");
                $stmt->execute([$teacher_id, $code, $percentage, $expiry_date]);
                header("Location: manage-coupons.php?success=1");
                exit;
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: manage-coupons.php");
    exit;
}

$editCoupon = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCoupon = $stmt->fetch();
}

$stmt = $pdo->query("SELECT coupons.*, teachers.name AS teacher_name FROM coupons LEFT JOIN teachers ON coupons.teacher_id = teachers.id ORDER BY coupons.id DESC");
$coupons = $stmt->fetchAll();

if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) $message = "Coupon added successfully.";
    if ($_GET['success'] == 2) $message = "Coupon updated successfully.";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxxify Academy</title>
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/file-upload.css">
    <link rel="stylesheet" href="assets/css/plyr.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="assets/css/full-calendar.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/editor-quill.css">
    <link rel="stylesheet" href="assets/css/apexcharts.css">
    <link rel="stylesheet" href="assets/css/calendar.css">
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css">
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
            <div class="container mt-5">
                <div class="row">
                    <!-- Left: Add/Edit Form -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5><?= $editCoupon ? 'Edit Coupon' : 'Add Coupon' ?></h5>
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php elseif (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?= $editCoupon['id'] ?? '' ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Teacher</label>
                                        <select name="teacher_id" class="form-control" required>
                                            <option value="">Select Teacher</option>
                                            <?php foreach ($teachers as $teacher): ?>
                                                <option value="<?= $teacher['id'] ?>" <?= (isset($editCoupon['teacher_id']) && $editCoupon['teacher_id'] == $teacher['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($teacher['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Code</label>
                                        <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($editCoupon['code'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Percentage</label>
                                        <input type="number" name="percentage" class="form-control" step="0.01" value="<?= htmlspecialchars($editCoupon['percentage'] ?? '') ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="date" name="expiry_date" class="form-control" value="<?= htmlspecialchars($editCoupon['expiry_date'] ?? '') ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><?= $editCoupon ? 'Update' : 'Add' ?></button>
                                    <?php if ($editCoupon): ?>
                                        <a href="manage-coupons.php" class="btn btn-secondary ms-2">Cancel</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Right: List of Coupons -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5>All Coupons</h5>
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Teacher</th>
                                            <th>Code</th>
                                            <th>Percentage</th>
                                            <th>Expiry Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($coupons as $coupon): ?>
                                            <tr>
                                                <td style="color:black;"><?= $coupon['id'] ?></td>
                                                <td style="color:black;">
                                                    <?= htmlspecialchars($coupon['teacher_name'] ?? '') ?>
                                                </td>
                                                <td style="color:black;"><?= htmlspecialchars($coupon['code']) ?></td>
                                                <td style="color:black;"><?= htmlspecialchars($coupon['percentage']) ?>%</td>
                                                <td style="color:black;"><?= htmlspecialchars($coupon['expiry_date']) ?></td>
                                                <td>
                                                    <a href="?edit=<?= $coupon['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="?delete=<?= $coupon['id'] ?>" onclick="return confirm('Delete this coupon?')" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($coupons)): ?>
                                            <tr>
                                                <td colspan="7">No coupons found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/phosphor-icon.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>