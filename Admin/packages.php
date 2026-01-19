<?php
require 'config.php';
require_once 'auth_check.php';

$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_point') {
            $package_id = $_POST['package_id'] ?? '';
            $point = $_POST['point'] ?? '';

            if ($package_id === '' || $point === '') {
                $error = "Both fields are required.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO packagepoints (package_id, point) VALUES (?, ?)");
                $stmt->execute([$package_id, $point]);
                $message = "Package point added successfully.";
            }
        } elseif ($_POST['action'] === 'update_package') {
            $package_id = $_POST['package_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';

            if ($package_id === '' || $name === '' || $price === '') {
                $error = "All fields are required.";
            } else {
                $stmt = $pdo->prepare("UPDATE packages SET name = ?, price = ? WHERE id = ?");
                $stmt->execute([$name, $price, $package_id]);
                $message = "Package updated successfully.";
            }
        } elseif ($_POST['action'] === 'update_point') {
            $point_id = $_POST['point_id'] ?? '';
            $package_id = $_POST['package_id'] ?? '';
            $point = $_POST['point'] ?? '';

            if ($point_id === '' || $package_id === '' || $point === '') {
                $error = "All fields are required.";
            } else {
                $stmt = $pdo->prepare("UPDATE packagepoints SET package_id = ?, point = ? WHERE id = ?");
                $stmt->execute([$package_id, $point, $point_id]);
                $message = "Package point updated successfully.";
            }
        } elseif ($_POST['action'] === 'delete_point') {
            $point_id = $_POST['point_id'] ?? '';

            if ($point_id === '') {
                $error = "Point ID is required.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM packagepoints WHERE id = ?");
                $stmt->execute([$point_id]);
                $message = "Package point deleted successfully.";
            }
        }
    } else {
        $package_id = $_POST['package_id'] ?? '';
        $point = $_POST['point'] ?? '';

        if ($package_id === '' || $point === '') {
            $error = "Both fields are required.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO packagepoints (package_id, point) VALUES (?, ?)");
            $stmt->execute([$package_id, $point]);
            $message = "Package point added successfully.";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
$packages = $stmt->fetchAll();

$total_points_query = $pdo->query("SELECT COUNT(*) as total FROM packagepoints");
$total_points = $total_points_query->fetch()['total'];
$total_pages = ceil($total_points / $items_per_page);

$stmt = $pdo->prepare("SELECT pp.*, p.name as package_name FROM packagepoints pp 
                     LEFT JOIN packages p ON pp.package_id = p.id 
                     ORDER BY pp.id ASC LIMIT ? OFFSET ?");
$stmt->execute([$items_per_page, $offset]);
$packagePoints = $stmt->fetchAll();
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
        <div class="row gy-4">
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Packages</h5>
                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                                <?php elseif (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>
                                
                                <form method="post" class="mb-4">
                                    <input type="hidden" name="action" value="add_point">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="form-label">Package</label>
                                            <select name="package_id" class="form-control" required>
                                                <option value="">Select Package</option>
                                                <?php foreach ($packages as $package): ?>
                                                    <option value="<?= $package['id'] ?>"><?= htmlspecialchars($package['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Point</label>
                                            <input type="text" name="point" class="form-control" placeholder="Enter point" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary w-100">Save</button>
                                        </div>
                                    </div>
                                </form>
                                
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($packages as $package): ?>
                                            <tr>
                                                <td style="color: black;"><?= $package['id'] ?></td>
                                                <td style="color: black;"><?= htmlspecialchars($package['name']) ?></td>
                                                <td style="color: black;"><?= htmlspecialchars($package['price']) ?> Rs</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="editPackage(<?= $package['id'] ?>, '<?= htmlspecialchars($package['name']) ?>', '<?= htmlspecialchars($package['price']) ?>')">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($packages)): ?>
                                            <tr><td colspan="4">No packages found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5>Package Points</h5>
                                <table class="table table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th>Package Name</th>
                                            <th>Point</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($packagePoints as $point): ?>
                                            <tr>
                                                <td style="color: black;"><?= htmlspecialchars($point['package_name']) ?></td>
                                                <td style="color: black;"><?= htmlspecialchars($point['point']) ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary me-1" onclick="editPoint(<?= $point['id'] ?>, <?= $point['package_id'] ?>, '<?= htmlspecialchars($point['point']) ?>')">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deletePoint(<?= $point['id'] ?>)">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($packagePoints)): ?>
                                            <tr><td colspan="3">No package points found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                
                                <?php if ($total_pages > 1): ?>
                                    <div class="text-center mt-5">
                                        <div class="pagination-info mb-4">
                                            <span class="badge bg-primary fs-4 px-4 py-3" style="font-weight: 600;">Showing <?= $offset + 1 ?> to <?= min($offset + $items_per_page, $total_points) ?> of <?= $total_points ?> package points</span>
                                        </div>
                                        <nav aria-label="Package Points Pagination">
                                            <ul class="pagination pagination-lg justify-content-center mb-0" style="font-size: 1.2rem;">
                                                <?php if ($current_page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link text-primary border-primary fw-bold" href="?page=<?= $current_page - 1 ?>" style="border-radius: 12px 0 0 12px; padding: 10px 15px; font-size: 1rem;">
                                                            <i class="ph ph-arrow-left" style="font-size: 1.2rem;"></i> Previous
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                
                                                <?php
                                                $start_page = max(1, $current_page - 2);
                                                $end_page = min($total_pages, $current_page + 2);
                                                
                                                if ($start_page > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link text-primary border-primary fw-bold" href="?page=1" style="padding: 10px 15px; font-size: 1rem;">1</a>
                                                    </li>
                                                    <?php if ($start_page > 2): ?>
                                                        <li class="page-item disabled">
                                                            <span class="page-link border-primary fw-bold" style="padding: 10px 15px; font-size: 1rem;">...</span>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                
                                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                                    <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                                        <a class="page-link <?= $i === $current_page ? 'bg-primary border-primary text-white' : 'text-primary border-primary' ?> fw-bold" href="?page=<?= $i ?>" style="padding: 10px 15px; font-size: 1rem;"><?= $i ?></a>
                                                    </li>
                                                <?php endfor; ?>
                                                
                                                <?php if ($end_page < $total_pages): ?>
                                                    <?php if ($end_page < $total_pages - 1): ?>
                                                        <li class="page-item disabled">
                                                            <span class="page-link border-primary fw-bold" style="padding: 10px 15px; font-size: 1rem;">...</span>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li class="page-item">
                                                        <a class="page-link text-primary border-primary fw-bold" href="?page=<?= $total_pages ?>" style="padding: 10px 15px; font-size: 1rem;"><?= $total_pages ?></a>
                                                    </li>
                                                <?php endif; ?>
                                                
                                                <?php if ($current_page < $total_pages): ?>
                                                    <li class="page-item">
                                                        <a class="page-link text-primary border-primary fw-bold" href="?page=<?= $current_page + 1 ?>" style="border-radius: 0 12px 12px 0; padding: 10px 15px; font-size: 1rem;">
                                                            Next <i class="ph ph-arrow-right" style="font-size: 1.2rem;"></i>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center mt-3">
                                        <span class="badge bg-secondary fs-6">Showing all <?= $total_points ?> package points</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_package">
                    <input type="hidden" name="package_id" id="edit_package_id">
                    <div class="mb-3">
                        <label for="edit_package_name" class="form-label">Package Name</label>
                        <input type="text" class="form-control" id="edit_package_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_package_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_package_price" name="price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Package</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPointModal" tabindex="-1" aria-labelledby="editPointModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPointModalLabel">Edit Package Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_point">
                    <input type="hidden" name="point_id" id="edit_point_id">
                    <div class="mb-3">
                        <label for="edit_point_package_id" class="form-label">Package</label>
                        <select name="package_id" id="edit_point_package_id" class="form-control" required>
                            <option value="">Select Package</option>
                            <?php foreach ($packages as $package): ?>
                                <option value="<?= $package['id'] ?>"><?= htmlspecialchars($package['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_point_value" class="form-label">Point</label>
                        <input type="text" class="form-control" id="edit_point_value" name="point" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Point</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deletePointForm" method="post" style="display: none;">
    <input type="hidden" name="action" value="delete_point">
    <input type="hidden" name="point_id" id="delete_point_id">
</form>
    
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

<script>
function editPackage(id, name, price) {
    document.getElementById('edit_package_id').value = id;
    document.getElementById('edit_package_name').value = name;
    document.getElementById('edit_package_price').value = price;
    
    var modal = new bootstrap.Modal(document.getElementById('editPackageModal'));
    modal.show();
}

function editPoint(id, packageId, point) {
    document.getElementById('edit_point_id').value = id;
    document.getElementById('edit_point_package_id').value = packageId;
    document.getElementById('edit_point_value').value = point;
    
    var modal = new bootstrap.Modal(document.getElementById('editPointModal'));
    modal.show();
}

function deletePoint(id) {
    if (confirm('Are you sure you want to delete this package point?')) {
        document.getElementById('delete_point_id').value = id;
        document.getElementById('deletePointForm').submit();
    }
}
</script>
    
</body>
</html>