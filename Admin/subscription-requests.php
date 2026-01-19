<?php
require 'config.php';
require_once 'auth_check.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'], $_POST['new_status'])) {
  $update_id = intval($_POST['update_id']);
  $new_status = $_POST['new_status'];
  $stmt = $pdo->prepare("UPDATE subscriptions SET status = ? WHERE id = ?");
  $stmt->execute([$new_status, $update_id]);
  header("Location: subscription-requests.php");
  exit;
}
$stmt = $pdo->query("SELECT s.*, u.name AS user_name, c.code AS coupon_code FROM subscriptions s LEFT JOIN users u ON s.user_id = u.id LEFT JOIN coupons c ON s.coupon_id = c.id ORDER BY s.id DESC");
$subscriptions = $stmt->fetchAll();
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
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5>All Subscription Requests</h5>
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
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Duration</th>
                      <th>Payment Proof</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($subscriptions as $sub): ?>
                      <tr>
                        <td><?= $sub['id'] ?></td>
                        <td><?= htmlspecialchars($sub['user_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($sub['package_name']) ?></td>
                        <td><?= htmlspecialchars($sub['payment_method']) ?></td>
                        <td><?= htmlspecialchars($sub['total_price']) ?></td>
                        <td><?= htmlspecialchars($sub['status']) ?></td>
                        <td><?= htmlspecialchars($sub['coupon_code'] ?? '') ?></td>
                        <td><?= htmlspecialchars($sub['start_date']) ?></td>
                        <td><?= htmlspecialchars($sub['end_date']) ?></td>
                        <td><?= htmlspecialchars($sub['duration']) ?></td>
                        <td>
                          <?php if (!empty($sub['payment_proof'])): ?>
                            <a href="../payment_proofs/<?= htmlspecialchars($sub['payment_proof']) ?>" target="_blank">
                              <img src="../payment_proofs/<?= htmlspecialchars($sub['payment_proof']) ?>" alt="Proof" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                            </a>
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                        <td>
                          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="<?= $sub['id'] ?>" data-status="<?= $sub['status'] ?>">Update</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    <?php if (empty($subscriptions)): ?>
                      <tr>
                        <td colspan="12">No subscription requests found.</td>
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
  <!-- Status Update Modal -->
  <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" id="statusForm">
          <div class="modal-header">
            <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="update_id" id="modalUpdateId">
            <div class="mb-3">
              <label for="modalNewStatus" class="form-label">Status</label>
              <select name="new_status" id="modalNewStatus" class="form-select">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    var statusModal = document.getElementById('statusModal');
    statusModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var status = button.getAttribute('data-status');
      document.getElementById('modalUpdateId').value = id;
      document.getElementById('modalNewStatus').value = status;
    });
  </script>
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