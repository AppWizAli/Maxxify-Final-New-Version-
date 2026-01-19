<?php
require_once 'auth_check.php';
require 'config.php';
$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

// Fetch teacher name
$teacher_name = '';
if ($teacher_id) {
    $stmt = $pdo->prepare("SELECT name FROM teachers WHERE id = ?");
    $stmt->execute([$teacher_id]);
    $row = $stmt->fetch();
    $teacher_name = $row ? $row['name'] : '';
}

// Fetch all coupon IDs for this teacher
$coupons = [];
if ($teacher_id) {
    $stmt = $pdo->prepare("SELECT id FROM coupons WHERE teacher_id = ?");
    $stmt->execute([$teacher_id]);
    $coupons = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$subscriptions = [];
if (!empty($coupons)) {
    $in = str_repeat('?,', count($coupons) - 1) . '?';
    $query = "SELECT s.*, u.name AS user_name, c.code AS coupon_code FROM subscriptions s LEFT JOIN users u ON s.user_id = u.id LEFT JOIN coupons c ON s.coupon_id = c.id WHERE s.status = 'approved' AND s.coupon_id IN ($in) ORDER BY s.id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($coupons);
    $subscriptions = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Subscription Details - Maxxify Academy</title>
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
    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->
    <!-- ============================ Sidebar Start ============================ -->
    <?php include "sidebar.php" ?>
    <!-- ============================ Sidebar End  ============================ -->
    <div class="dashboard-main-wrapper">
        <?php include "Includes/Header.php" ?>
        <div class="dashboard-body">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Approved Subscriptions for Teacher: <?= htmlspecialchars($teacher_name) ?></h5>
                                <table class="table mt-2">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Package</th>
                                            <th>Payment Method</th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th>Coupon</th>
                                            <th>Teacher</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($subscriptions)): ?>
                                            <?php foreach ($subscriptions as $sub): ?>
                                                <tr>
                                                    <td><?= $sub['id'] ?></td>
                                                    <td><?= htmlspecialchars($sub['user_name'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($sub['package_name']) ?></td>
                                                    <td><?= htmlspecialchars($sub['payment_method']) ?></td>
                                                    <td><?= htmlspecialchars($sub['total_price']) ?></td>
                                                    <td><?= htmlspecialchars($sub['status']) ?></td>
                                                    <td><?= htmlspecialchars($sub['coupon_code'] ?? '') ?></td>
                                                    <td><?= htmlspecialchars($teacher_name) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8">No approved subscriptions found for this teacher's coupons.</td>
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
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <script src="assets/js/phosphor-icon.js"></script>
    <script src="assets/js/file-upload.js"></script>
    <script src="assets/js/plyr.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="assets/js/full-calendar.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/editor-quill.js"></script>
    <script src="assets/js/apexcharts.min.js"></script>
    <script src="assets/js/calendar.js"></script>
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>