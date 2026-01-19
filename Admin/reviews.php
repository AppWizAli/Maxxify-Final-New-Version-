<?php
require 'config.php';
require_once 'auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = $_POST['name'] ?? '';
            $number = $_POST['number'] ?? '';
            $review = $_POST['review'] ?? '';
            $field = $_POST['field'] ?? '';
            
            $image_path = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/reviews_images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image_name = uniqid('review_', true) . '.' . $ext;
                $image_path = 'reviews_images/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
            }
            
            $stmt = $pdo->prepare("INSERT INTO reviews (name, number, review, field, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $number, $review, $field, $image_path]);
            $message = "Review added successfully.";
        } elseif ($_POST['action'] === 'edit') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $number = $_POST['number'] ?? '';
            $review = $_POST['review'] ?? '';
            $field = $_POST['field'] ?? '';
            
            $image_path = $_POST['current_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/reviews_images/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image_name = uniqid('review_', true) . '.' . $ext;
                $image_path = 'reviews_images/' . $image_name;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
            }
            
            $stmt = $pdo->prepare("UPDATE reviews SET name = ?, number = ?, review = ?, field = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $number, $review, $field, $image_path, $id]);
            $message = "Review updated successfully.";
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? '';
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Review deleted successfully.";
        }
    }
}

$reviews = $pdo->query("SELECT * FROM reviews ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxxify Academy - Reviews</title>
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
                                <h5>Manage Reviews</h5>
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                                <?php endif; ?>
                                
                                <form method="post" enctype="multipart/form-data" class="mb-4">
                                    <input type="hidden" name="action" value="add">
                                                                         <div class="row">
                                         <div class="col-md-2">
                                             <div class="mb-3">
                                                 <label class="form-label">Image</label>
                                                 <input type="file" name="image" class="form-control" accept="image/*" required>
                                             </div>
                                         </div>
                                         <div class="col-md-2">
                                             <div class="mb-3">
                                                 <label class="form-label">Name</label>
                                                 <input type="text" name="name" class="form-control" required>
                                             </div>
                                         </div>
                                         <div class="col-md-2">
                                             <div class="mb-3">
                                                 <label class="form-label">Number</label>
                                                 <input type="number" name="number" class="form-control" required>
                                             </div>
                                         </div>
                                         <div class="col-md-3">
                                             <div class="mb-3">
                                                 <label class="form-label">Review</label>
                                                 <textarea name="review" class="form-control" rows="3" required></textarea>
                                             </div>
                                         </div>
                                         <div class="col-md-3">
                                             <div class="mb-3">
                                                 <label class="form-label">Field</label>
                                                 <input type="text" name="field" class="form-control" required>
                                             </div>
                                         </div>
                                     </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Add Review</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-striped" id="reviewsTable">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Number</th>
                                                <th>Review</th>
                                                <th>Field</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reviews as $review): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($review['image'])): ?>
                                                            <img src="<?= htmlspecialchars($review['image']) ?>" alt="Review Image" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <span class="text-muted">No image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                                                                         <td style="color: #000;"><?= htmlspecialchars($review['name']) ?></td>
                                                     <td style="color: #000;"><?= htmlspecialchars($review['number']) ?></td>
                                                     <td style="color: #000;"><?= htmlspecialchars($review['review']) ?></td>
                                                     <td style="color: #000;"><?= htmlspecialchars($review['field']) ?></td>
                                                     <td>
                                                         <button class="btn btn-sm btn-primary" onclick="editReview(<?= $review['id'] ?>, '<?= htmlspecialchars($review['name']) ?>', <?= $review['number'] ?>, '<?= htmlspecialchars($review['review']) ?>', '<?= htmlspecialchars($review['field']) ?>', '<?= htmlspecialchars($review['image']) ?>')">Edit</button>
                                                         <button class="btn btn-sm btn-danger" onclick="deleteReview(<?= $review['id'] ?>)">Delete</button>
                                                     </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="current_image" id="edit_current_image">
                    
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div id="current_image_display"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Image (optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Number</label>
                        <input type="number" name="number" id="edit_number" class="form-control" required>
                    </div>
                    
                                         <div class="mb-3">
                         <label class="form-label">Review</label>
                         <textarea name="review" id="edit_review" class="form-control" rows="3" required></textarea>
                     </div>
                     
                     <div class="mb-3">
                         <label class="form-label">Field</label>
                         <input type="text" name="field" id="edit_field" class="form-control" required>
                     </div>
                 </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteForm" method="post" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="delete_id">
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
$(document).ready(function() {
    $('#reviewsTable').DataTable();
});

function editReview(id, name, number, review, field, image) {
    $('#edit_id').val(id);
    $('#edit_name').val(name);
    $('#edit_number').val(number);
    $('#edit_review').val(review);
    $('#edit_field').val(field);
    $('#edit_current_image').val(image);
    
    if (image) {
        $('#current_image_display').html('<img src="' + image + '" style="width: 100px; height: 100px; object-fit: cover;">');
    } else {
        $('#current_image_display').html('<span class="text-muted">No image</span>');
    }
    
    $('#editModal').modal('show');
}

function deleteReview(id) {
    if (confirm('Are you sure you want to delete this review?')) {
        $('#delete_id').val(id);
        $('#deleteForm').submit();
    }
}
</script>
    
</body>
</html>